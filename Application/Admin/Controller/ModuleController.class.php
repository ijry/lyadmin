<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
// | 版权申明：零云不是一个自由软件，是零云官方推出的商业源码，严禁在未经许可的情况下
// | 拷贝、复制、传播、使用零云的任意代码，如有违反，请立即删除，否则您将面临承担相应
// | 法律责任的风险。如果需要取得官方授权，请联系官方http://www.lingyun.net
// +----------------------------------------------------------------------
namespace Admin\Controller;

use lyf\Sql;

/**
 * 功能模块控制器
 * @author jry <598821125@qq.com>
 */
class ModuleController extends AdminController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        $module_object = D('Module');
        $data_list     = $module_object->getAll();

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('模块列表') // 设置页面标题
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->setSearch('请输入ID/标题', U('index'))
            ->addTableColumn('name', '名称')
            ->addTableColumn('title', '标题')
            ->addTableColumn('description', '描述', '', '', '120')
            ->addTableColumn('developer', '开发者')
            ->addTableColumn('version', '版本')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('status_icon', '状态', 'text')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->display();
    }

    /**
     * 检查模块依赖
     * @author jry <598821125@qq.com>
     */
    public function checkDependence($dependences)
    {
        if (is_array($dependences)) {
            foreach ($dependences as $key => $val) {
                $con['name'] = $key;
                $module_info = D('Module')->where($con)->find();
                if (!$module_info) {
                    $this->error('该模块依赖' . $key . '模块');
                }
                if (version_compare($module_info['version'], $val) >= 0) {
                    continue;
                } else {
                    $this->error($module_info['title'] . '模块版本不得低于v' . $val);
                    return false;
                }
            }
            return true;
        }
    }

    /**
     * 安装模块之前
     * @author jry <598821125@qq.com>
     */
    public function install_before($name)
    {
        $db    = M();
        $table = $db->query($sql = 'show tables');
        $tarr  = array();

        //取出所有表格并组成一维数组
        foreach ($table as $vt) {
            $tarr[] = $vt["Tables_in_lingyun_qunzhan"];
        }
        $sql_file = realpath(APP_DIR . $name) . '/Sql/install.sql';
        $content  = file_get_contents($sql_file); //读取sql文件
        $pattern1 = "/CREATE TABLE.*`ly_[a-zA-Z]+_[a-zA-Z]+(_[a-zA-Z]+)*`/"; //捕捉创建表格语句
        $pattern2 = "/ly_[a-zA-Z]+_[a-zA-Z]+(_[a-zA-Z]+)*/"; //捕捉创建语句中的表格名
        preg_match_all($pattern1, $content, $create); //正则取出所有创建语句
        $table = array();

        //循环取出所有表格名，并放入数组中
        foreach ($create[0] as $vc) {
            preg_match_all($pattern2, $vc, $ta);
            $table[] = $ta[0][0];
        }
        $info  = array();
        $info2 = array();
        foreach ($table as $ko => &$vo) {
            foreach ($tarr as $vt) {
                if ($vo == $vt) {
                    $info[] = $vo;
                    unset($table[$ko]);
                }
            }
        }
        $danger = '';

        //冲突表格显示
        if ($info) {
            $danger .= '<div class="alert alert-danger">';
            foreach ($info as $vi) {
                $danger .= '<p>表格' . $vi . '有冲突！</p>';
            }
            $danger .= '</div>';
        }
        if ($table) {
            $danger .= '<div class="alert alert-success">';
            foreach ($table as $vt) {
                $danger .= '<p>表格' . $vt . '无冲突。</p>';
            }
            $danger .= '</div>';
        }

        // 使用FormBuilder快速建立表单页面
        $builder = new \lyf\builder\FormBuilder();
        $builder->setMetaTitle('准备安装模块') // 设置页面标题
            ->setPostUrl(U('install')) // 设置表单提交地址
            ->setExtraHtml($danger, 'top')
            ->addFormItem('name', 'hidden', 'name', 'name')
            ->addFormItem('clear', 'radio', '清除历史数据', '是否清除历史数据', array('1' => '是', '0' => '否'))
            ->setFormData(array('name' => $name))
            ->display();
    }

    /**
     * 安装模块
     * @author jry <598821125@qq.com>
     */
    public function install($name, $clear = true)
    {
        // 演示模式
        if (APP_DEMO === true) {
            $this->error('演示模式不允许该操作！');
        }

        // 获取当前模块信息
        $config_file = realpath(APP_DIR . $name) . '/'
        . D('Module')->install_file();
        if (!$config_file) {
            $this->error('安装失败');
        }
        $config_info = include $config_file;
        $data        = $config_info['info'];

        // 处理模块配置
        if ($config_info['config']) {
            $temp_arr = $config_info['config'];
            foreach ($temp_arr as $key => $value) {
                if ($value['type'] == 'group') {
                    foreach ($value['options'] as $gkey => $gvalue) {
                        foreach ($gvalue['options'] as $ikey => $ivalue) {
                            $config[$ikey] = $ivalue['value'];
                        }
                    }
                } else {
                    $config[$key] = $temp_arr[$key]['value'];
                }
            }
            $data['config'] = json_encode($config);
        } else {
            $data['config'] = '';
        }

        // 检查依赖
        if ($data['dependences']) {
            $result = $this->checkDependence($data['dependences']);
            if (!$result) {
                return false;
            }
        }

        // 获取后台菜单
        if ($config_info['admin_menu']) {
            // 将key值赋给id
            foreach ($config_info['admin_menu'] as $key => &$val) {
                $val['id'] = (string) $key;
            }
            $data['admin_menu'] = json_encode($config_info['admin_menu']);
        }

        // 获取用户中心导航
        if ($config_info['user_nav']) {
            $data['user_nav'] = json_encode($config_info['user_nav']);
        } else {
            $data['user_nav'] = '';
        }

        // 获取用户主页导航
        if ($config_info['home_nav']) {
            $data['home_nav'] = json_encode($config_info['home_nav']);
        } else {
            $data['home_nav'] = '';
        }

        // 清除旧数据
        $sql_object           = new Sql();
        $uninstall_sql_status = true;
        if ($clear) {
            $sql_file             = realpath(APP_DIR . $name) . '/Sql/uninstall.sql';
            if (file_exists($sql_file)) {
                $uninstall_sql_status = $sql_object->execute_sql_from_file($sql_file);
            }
        }
        if (!$uninstall_sql_status) {
            $this->error('安装失败');
        }

        // 安装新数据库
        $sql_file   = realpath(APP_DIR . $name) . '/Sql/install.sql';
        $sql_status = true;
        if (file_exists($sql_file)) {
            $sql_status = $sql_object->execute_sql_from_file($sql_file);
        }

        if ($sql_status) {
            // 写入数据库记录
            $module_object = D('Module');
            $data          = $module_object->create($data);
            if ($data) {
                $id = $module_object->add($data);
                if ($id) {
                    $this->success('安装成功', U('index'));
                } else {
                    $this->error('安装失败：' . $module_object->getError());
                }
            } else {
                $this->error($module_object->getError());
            }
        } else {
            $sql_file   = realpath(APP_DIR . $name) . '/Sql/uninstall.sql';
            if (file_exists($sql_file)) {
                $sql_status = $sql_object->execute_sql_from_file($sql_file);
            }
            $this->error('安装失败');
        }
    }

    /**
     * 卸载模块之前
     * @author jry <598821125@qq.com>
     */
    public function uninstall_before($id)
    {
        // 使用FormBuilder快速建立表单页面
        $builder = new \lyf\builder\FormBuilder();
        $builder->setMetaTitle('准备卸载模块') // 设置页面标题
            ->setPostUrl(U('uninstall')) // 设置表单提交地址
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('clear', 'radio', '是否清除数据', '是否清除数据', array('1' => '是', '0' => '否'))
            ->setFormData(array('id' => $id))
            ->display();
    }

    /**
     * 卸载模块
     * @author jry <598821125@qq.com>
     */
    public function uninstall($id, $clear = false)
    {
        // 演示模式
        if (APP_DEMO === true) {
            $this->error('演示模式不允许该操作！');
        }

        $module_object = D('Module');
        $module_info   = $module_object->find($id);
        if ($module_info['is_system'] === '1') {
            $this->error('系统模块不允许卸载！');
        }
        $result = $module_object->delete($id);
        if ($result) {
            if ($clear) {
                $sql_object = new Sql();
                $sql_file   = realpath(APP_DIR . $module_info['name']) . '/Sql/uninstall.sql';
                $sql_status = $sql_object->execute_sql_from_file($sql_file);
                if ($sql_status) {
                    $this->success('卸载成功，相关数据彻底删除！', U('index'));
                }
            } else {
                $this->success('卸载成功，相关数据未卸载！', U('index'));
            }
        } else {
            $this->error('卸载失败', U('index'));
        }
    }

    /**
     * 更新模块信息
     * @author jry <598821125@qq.com>
     */
    public function updateInfo($id)
    {
        $module_object = D('Module');
        $name          = $module_object->getFieldById($id, 'name');
        $config_file   = realpath(APP_DIR . $name) . '/'
        . D('Module')->install_file();
        if (!$config_file) {
            $this->error('不存在安装文件');
        }
        $config_info = include $config_file;
        $data        = $config_info['info'];

        // 读取数据库已有配置
        $db_moduel_config = D('Module')->getFieldByName($name, 'config');
        $db_moduel_config = json_decode($db_moduel_config, true);

        // 处理模块配置
        if ($config_info['config']) {
            $temp_arr = $config_info['config'];
            foreach ($temp_arr as $key => $value) {
                if ($value['type'] == 'group') {
                    foreach ($value['options'] as $gkey => $gvalue) {
                        foreach ($gvalue['options'] as $ikey => $ivalue) {
                            $config[$ikey] = $ivalue['value'];
                        }
                    }
                } else {
                    if (isset($db_moduel_config[$key])) {
                        $config[$key] = $db_moduel_config[$key];
                    } else {
                        $config[$key] = $temp_arr[$key]['value'];
                    }
                }
            }
            $data['config'] = json_encode($config);
        } else {
            $data['config'] = '';
        }

        // 获取用户中心导航
        if ($config_info['user_nav']) {
            $data['user_nav'] = json_encode($config_info['user_nav']);
        } else {
            $data['user_nav'] = '';
        }

        // 获取用户主页导航
        if ($config_info['home_nav']) {
            $data['home_nav'] = json_encode($config_info['home_nav']);
        } else {
            $data['home_nav'] = '';
        }

        // 获取后台菜单
        if ($config_info['admin_menu']) {
            // 将key值赋给id
            foreach ($config_info['admin_menu'] as $key => &$val) {
                $val['id'] = (string) $key;
            }
            $data['admin_menu'] = json_encode($config_info['admin_menu']);
        }

        // 获取默认路由
        if ($config_info['router']) {
            $data['router'] = json_encode($config_info['router']);
        } else {
            $data['router'] = '';
        }

        $data['id'] = $id;
        $data       = $module_object->create($data);
        if ($data) {
            $id = $module_object->save($data);
            if ($id) {
                $this->success('更新成功', U('index'));
            } else {
                $this->error('更新失败：' . $module_object->getError());
            }
        } else {
            $this->error($module_object->getError());
        }
    }

    /**
     * 设置一条或者多条数据的状态
     * @author jry <598821125@qq.com>
     */
    public function setStatus($model = '', $strict = null)
    {
        if ('' == $model) {
            $model = request()->controller();
        }
        $ids = I('request.ids');
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $is_system = D($model)->getFieldById($id, 'is_system');
                if ($is_system) {
                    $this->error('系统模块不允许操作');
                }
            }
        } else {
            $is_system = D($model)->getFieldById($ids, 'is_system');
            if ($is_system) {
                $this->error('系统模块不允许操作');
            }
        }
        parent::setStatus($model);
    }
}

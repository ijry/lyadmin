<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Util\Sql;

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

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('模块列表') // 设置页面标题
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->setSearch('请输入ID/标题', U('index'))
            ->addTableColumn('name', '名称')
            ->addTableColumn('title', '标题')
            ->addTableColumn('description', '描述')
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
        // 使用FormBuilder快速建立表单页面。
        $builder = new \Common\Builder\FormBuilder();
        $builder->setMetaTitle('准备安装模块') // 设置页面标题
            ->setPostUrl(U('install')) // 设置表单提交地址
            ->addFormItem('name', 'hidden', 'name', 'name')
            ->addFormItem('clear', 'radio', '是否清除历史数据', '是否清除历史数据', array('1' => '是', '0' => '否'))
            ->setFormData(array('name' => $name))
            ->display();
    }

    /**
     * 安装模块
     * @author jry <598821125@qq.com>
     */
    public function install($name, $clear = true)
    {
        // 获取当前模块信息
        $config_file = realpath(APP_PATH . $name) . '/'
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

        // 安装数据库
        $sql_object           = new Sql();
        $uninstall_sql_status = true;
        // 清除旧数据
        if ($clear) {
            $sql_file             = realpath(APP_PATH . $name) . '/Sql/uninstall.sql';
            $uninstall_sql_status = $sql_object->execute_sql_from_file($sql_file);
        }
        // 安装新数据表
        if (!$uninstall_sql_status) {
            $this->error('安装失败');
        }
        $sql_file   = realpath(APP_PATH . $name) . '/Sql/install.sql';
        $sql_status = $sql_object->execute_sql_from_file($sql_file);

        if ($sql_status) {
            // 写入数据库记录
            $module_object = D('Module');
            $data          = $module_object->create($data);
            if ($data) {
                $id = $module_object->add($data);
                if ($id) {
                    // 安装成功后自动在前台新增导航
                    $nav_data['group'] = 'top';
                    $nav_data['title'] = $data['title'];
                    $nav_data['type']  = 'module';
                    $nav_data['value'] = $data['name'];
                    $nav_data['icon']  = $data['icon'] ?: '';
                    $nav_object        = D('Nav');
                    $nav_data_created  = $nav_object->create($nav_data);
                    if ($nav_data_created) {
                        $nav_add_result = $nav_object->add($nav_data_created);
                    }
                    $this->success('安装成功', U('index'));
                } else {
                    $this->error('安装失败');
                }
            } else {
                $this->error($module_object->getError());
            }
        } else {
            $sql_file   = realpath(APP_PATH . $name) . '/Sql/uninstall.sql';
            $sql_status = $sql_object->execute_sql_from_file($sql_file);
            $this->error('安装失败');
        }
    }

    /**
     * 卸载模块之前
     * @author jry <598821125@qq.com>
     */
    public function uninstall_before($id)
    {
        // 使用FormBuilder快速建立表单页面。
        $builder = new \Common\Builder\FormBuilder();
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
        $module_object = D('Module');
        $module_info   = $module_object->find($id);
        if ($module_info['is_system'] === '1') {
            $this->error('系统模块不允许卸载！');
        }
        $result = $module_object->delete($id);
        if ($result) {
            if ($clear) {
                $sql_object = new Sql();
                $sql_file   = realpath(APP_PATH . $module_info['name']) . '/Sql/uninstall.sql';
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
        $config_file   = realpath(APP_PATH . $name) . '/'
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

        $data['id'] = $id;
        $data       = $module_object->create($data);
        if ($data) {
            $id = $module_object->save($data);
            if ($id) {
                $this->success('更新成功', U('index'));
            } else {
                $this->error('更新失败');
            }
        } else {
            $this->error($module_object->getError());
        }
    }

    /**
     * 设置一条或者多条数据的状态
     * @author jry <598821125@qq.com>
     */
    public function setStatus($model = CONTROLLER_NAME, $script = false)
    {
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

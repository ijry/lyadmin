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
namespace Site\Admin;

use Admin\Controller\AdminController;
use lyf\Page;
use lyf\Sql;

/**
 * 模板控制器
 * @author jry <598821125@qq.com>
 */
class ThemeAdmin extends AdminController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        $module_object = D('Theme');
        $data_list     = $module_object->getAll($is_install);

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('模板列表') // 设置页面标题
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
    public function checkDependence($dependences = '')
    {
        return true;
    }

    /**
     * 安装之前
     * @author jry <598821125@qq.com>
     */
    public function install_before($name)
    {
        // 使用FormBuilder快速建立表单页面
        $builder = new \lyf\builder\FormBuilder();
        $builder->setMetaTitle('准备安装模板') // 设置页面标题
            ->setPostUrl(U('install')) // 设置表单提交地址
            ->setExtraHtml('<div class="alert alert-danger">清除网站数据会删掉站点模块的幻灯切换、文章分类、所有文章等信息，请确保您有备份数据。</div><div class="alert alert-danger">清除网站数据会删掉站点模块的幻灯切换、文章分类、所有文章等信息，请确保您有备份数据。</div><div class="alert alert-danger">清除网站数据会删掉站点模块的幻灯切换、文章分类、所有文章等信息，请确保您有备份数据。</div>', 'top')
            ->addFormItem('name', 'hidden', 'name', 'name')
            ->addFormItem('clear', 'radio', '清除网站数据', '清除网站数据将永久无法找回', array('1' => '是', '0' => '否'))
            ->setFormData(array('name' => $name))
            ->display();
    }

    /**
     * 演示数据
     * @author jry <598821125@qq.com>
     */
    public function init_data($id){
        // 安装演示数据
        $sql_object = new Sql();
        $sql_file = realpath(APP_DIR . 'Site') . '/Sql/uninstall_data.sql';
        if (file_exists($sql_file)) {
            $uninstall_sql_status = $sql_object->execute_sql_from_file($sql_file);
        } else {
            $uninstall_sql_status = true;
        }
        if (!$uninstall_sql_status) {
            $this->error('安装失败');
        }
        $theme_info = D('Theme')->find($id);
        $sql_file   = realpath(APP_DIR . 'Site/View/Site/theme/' . $theme_info['name_path']) . '/install.sql';
        $sql_status = true;
        if (file_exists($sql_file)) {
            // 转义
            $content = str_replace(
                array('[sql_module_name]', '[sql_site_id]', '[sql_uid]', '[sql_theme_id]'),
                array('site', 1, is_login(), $id),
                file_get_contents($sql_file)
            );
            $sql_status = $sql_object->execute_sql($content);
        } else {
            echo '不存在Sql';
        }
    }

    /**
     * 安装
     * @author jry <598821125@qq.com>
     */
    public function install($name, $clear = false)
    {
        // 演示模式
        if (APP_DEMO === true) {
            $this->error('演示模式不允许该操作！');
        }

        // 获取当前模块信息
        $config_file = realpath(APP_DIR . 'Site/View/Site/theme/'. str_replace(array('_'),array('/'), $name)) . '/'. D('Theme')->install_file();
        if (!$config_file) {
            $this->error('安装失败');
        }
        $config_info  = include $config_file;
        $data         = $config_info['info'];
        $data['name'] = $name;
        $data['cid']  = 1;
        $data['is_public']  = 1;

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

        // 写入数据库记录
        $module_object = D('Theme');
        $data          = $module_object->create($data);
        if ($data) {
            $id = $module_object->add($data);
            if ($id) {
                // 创建订单
                $order_data                = array();
                $order_data['site_id']     = 1;
                $order_data['theme_id']    = $id;
                $order_data['create_time'] = time();
                $order_data['update_time'] = time();
                $order_data['uid']         = is_login();
                $order_data['status']      = 1;
                $ret = D('Order')->add($order_data);

                // 清除旧数据
                $sql_object           = new Sql();
                $uninstall_sql_status = true;
                if ($clear) {
                    $sql_file = realpath(APP_DIR . 'Site') . '/Sql/uninstall_data.sql';
                    if (file_exists($sql_file)) {
                        $uninstall_sql_status = $sql_object->execute_sql_from_file($sql_file);
                    } else {
                        $uninstall_sql_status = true;
                    }

                    if (!$uninstall_sql_status) {
                        $this->error('安装失败');
                    }

                    // 安装演示数据
                    $sql_file   = realpath(APP_DIR . 'Site/View/Site/theme/' . str_replace(array('_'),array('/'), $name)) . '/install.sql';
                    $sql_status = true;
                    if (file_exists($sql_file)) {
                        // 转义
                        $content = str_replace(
                            array('[sql_module_name]', '[sql_site_id]', '[sql_uid]', '[sql_theme_id]', '[module_name]'),
                            array('site', 1, is_login(), $id, 'Site'),
                            file_get_contents($sql_file)
                        );
                        $sql_status = $sql_object->execute_sql($content);
                    }
                }
                $this->success('安装成功', U('index'));
            } else {
                $this->error('安装失败：' . $module_object->getError());
            }
        } else {
            $this->error($module_object->getError());
        }
    }

    /**
     * 卸载之前
     * @author jry <598821125@qq.com>
     */
    public function uninstall_before($id)
    {
        // 使用FormBuilder快速建立表单页面
        $builder = new \lyf\builder\FormBuilder();
        $builder->setMetaTitle('准备卸载模块') // 设置页面标题
            ->setPostUrl(U('uninstall')) // 设置表单提交地址
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->setExtraHtml('<div class="alert alert-danger">清除网站数据会删掉站点模块的幻灯切换、文章分类、所有文章等信息，请确保您有备份数据。</div><div class="alert alert-danger">清除网站数据会删掉站点模块的幻灯切换、文章分类、所有文章等信息，请确保您有备份数据。</div><div class="alert alert-danger">清除网站数据会删掉站点模块的幻灯切换、文章分类、所有文章等信息，请确保您有备份数据。</div>', 'top')
            ->addFormItem('clear', 'radio', '清除网站数据', '清除网站数据将永久无法找回', array('1' => '是', '0' => '否'))
            ->setFormData(array('id' => $id))
            ->display();
    }

    /**
     * 卸载
     * @author jry <598821125@qq.com>
     */
    public function uninstall($id, $clear = false)
    {
        // 演示模式
        if (APP_DEMO === true) {
            $this->error('演示模式不允许该操作！');
        }

        $module_object = D('Theme');
        $module_info   = $module_object->find($id);
        $result = $module_object->delete($id);
        if ($result) {
            if ($clear) {
                $sql_object = new Sql();
                $sql_file   = realpath(APP_DIR . 'Site/View/Site/theme/' . $module_info['name_path']) . '/uninstall.sql';
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
     * 更新信息
     * @author jry <598821125@qq.com>
     */
    public function updateInfo($id)
    {
    }
}

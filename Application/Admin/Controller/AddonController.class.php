<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Think\Page;
use Util\Sql;

/**
 * 扩展后台管理页面
 * 该类参考了OneThink的部分实现
 * @author jry <598821125@qq.com>
 */
class AddonController extends AdminController
{
    /**
     * 插件列表
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 获取所有插件信息
        $p            = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $addon_object = D('Addon');
        $addons       = $addon_object
            ->getAllAddon();

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('插件列表') // 设置页面标题
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->addTableColumn('name', '标识')
            ->addTableColumn('title', '名称')
            ->addTableColumn('description', '描述')
            ->addTableColumn('status', '状态')
            ->addTableColumn('author', '作者')
            ->addTableColumn('version', '版本')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($addons) // 数据列表
            ->display();
    }

    /**
     * 设置插件页面
     * @author jry <598821125@qq.com>
     */
    public function config()
    {
        if (IS_POST) {
            $id     = (int) I('id');
            $config = I('config');
            $flag   = D('Addon')
                ->where("id={$id}")
                ->setField('config', json_encode($config));
            if ($flag !== false) {
                $this->success('保存成功', U('index'));
            } else {
                $this->error('保存失败');
            }
        } else {
            $id    = (int) I('id');
            $addon = D('Addon')->find($id);
            if (!$addon) {
                $this->error('插件未安装');
            }
            $addon_class = get_addon_class($addon['name']);
            if (!class_exists($addon_class)) {
                trace("插件{$addon['name']}无法实例化,", 'ADDONS', 'ERR');
            }
            $data                   = new $addon_class;
            $addon['addon_path']    = $data->addon_path;
            $addon['custom_config'] = $data->custom_config;
            $this->meta_title       = '设置插件-' . $data->info['title'];
            $db_config              = $addon['config'];
            $addon['config']        = include $data->config_file;
            if ($db_config) {
                $db_config = json_decode($db_config, true);
                foreach ($addon['config'] as $key => $value) {
                    if ($value['type'] != 'group') {
                        $addon['config'][$key]['value'] = $db_config[$key];
                    } else {
                        foreach ($value['options'] as $gourp => $options) {
                            foreach ($options['options'] as $gkey => $value) {
                                $addon['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
                            }
                        }
                    }
                }
            }
            // 构造表单名
            foreach ($addon['config'] as $key => $val) {
                if ($val['type'] == 'group') {
                    foreach ($val['options'] as $key2 => $val2) {
                        foreach ($val2['options'] as $key3 => $val3) {
                            $addon['config'][$key]['options'][$key2]['options'][$key3]['name'] = 'config[' . $key3 . ']';
                        }
                    }
                } else {
                    $addon['config'][$key]['name'] = 'config[' . $key . ']';
                }
            }
            $this->assign('data', $addon);
            $this->assign('form_items', $addon['config']);
            if ($addon['custom_config']) {
                $this->assign('custom_config', $this->fetch($addon['addon_path'] . $addon['custom_config']));
                $this->display($addon['addon_path'] . $addon['custom_config']);
            } else {
                //使用FormBuilder快速建立表单页面。
                $builder = new \Common\Builder\FormBuilder();
                $builder->setMetaTitle('插件设置') //设置页面标题
                    ->setPostUrl(U('config')) //设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->setExtraItems($addon['config']) //直接设置表单数据
                    ->setFormData($addon)
                    ->display();
            }
        }
    }

    /**
     * 安装插件
     * @author jry <598821125@qq.com>
     */
    public function install()
    {
        $addon_name = trim(I('addon_name'));
        $class      = get_addon_class($addon_name);
        if (!class_exists($class)) {
            $this->error('插件不存在');
        }
        $addons = new $class;
        $info   = $addons->info;
        $hooks  = $addons->hooks;

        // 检测信息的正确性
        if (!$info || !$addons->checkInfo()) {
            $this->error('插件信息缺失');
        }
        session('addons_install_error', null);
        $install_flag = $addons->install();
        if (!$install_flag) {
            $this->error('执行插件预安装操作失败' . session('addons_install_error'));
        }

        // 检查该插件所需的钩子
        if ($hooks) {
            $hook_object = D('Hook');
            foreach ($hooks as $val) {
                $hook_object->existHook($val, array('description' => $info['description']));
            }
        }

        // 安装数据库
        $sql_file = realpath(C('ADDON_PATH') . $addon_name) . '/Sql/install.sql';
        if (file_exists($sql_file)) {
            $sql_object = new Sql();
            $sql_status = $sql_object->execute_sql_from_file($sql_file);
            if (!$sql_status) {
                $this->error('执行插件SQL安装语句失败' . session('addons_install_error'));
            }
        }

        $addon_object = D('Addon');
        $data         = $addon_object->create($info);
        if (is_array($addons->admin_list) && $addons->admin_list !== array()) {
            $data['adminlist'] = 1;
        } else {
            $data['adminlist'] = 0;
        }
        if (!$data) {
            $this->error($addon_object->getError());
        }
        if ($addon_object->add($data)) {
            $config = array('config' => json_encode($addons->getConfig()));
            $addon_object->where("name='{$addon_name}'")->save($config);
            $hooks_update = D('Hook')->updateHooks($addon_name);
            if ($hooks_update) {
                S('hooks', null);
                $this->success('安装成功');
            } else {
                $addon_object->where("name='{$addon_name}'")->delete();
                $this->error('更新钩子处插件失败,请卸载后尝试重新安装');
            }
        } else {
            $this->error('写入插件数据失败');
        }
    }

    /**
     * 卸载插件
     * @author jry <598821125@qq.com>
     */
    public function uninstall()
    {
        $addon_object = D('Addon');
        $id           = trim(I('id'));
        $db_addons    = $addon_object->find($id);
        $class        = get_addon_class($db_addons['name']);
        $this->assign('jumpUrl', U('index'));
        if (!$db_addons || !class_exists($class)) {
            $this->error('插件不存在');
        }
        session('addons_uninstall_error', null);
        $addons         = new $class;
        $uninstall_flag = $addons->uninstall();
        if (!$uninstall_flag) {
            $this->error('执行插件预卸载操作失败' . session('addons_uninstall_error'));
        }
        $hooks_update = D('Hook')->removeHooks($db_addons['name']);
        if ($hooks_update === false) {
            $this->error('卸载插件所挂载的钩子数据失败');
        }
        S('hooks', null);
        $delete = $addon_object->where("name='{$db_addons['name']}'")->delete();

        // 卸载数据库
        $sql_file = realpath(C('ADDON_PATH') . $db_addons['name']) . '/Sql/uninstall.sql';
        if (file_exists($sql_file)) {
            $sql_object = new Sql();
            $sql_status = $sql_object->execute_sql_from_file($sql_file);
            if (!$sql_status) {
                $this->error('执行插件SQL卸载语句失败' . session('addons_uninstall_error'));
            }
        }

        if ($delete === false) {
            $this->error('卸载插件失败');
        } else {
            $this->success('卸载成功');
        }
    }

    /**
     * 外部执行插件方法
     * @author jry <598821125@qq.com>
     */
    public function execute($_addons = null, $_controller = null, $_action = null)
    {
        if (C('URL_CASE_INSENSITIVE')) {
            $_addons     = ucfirst(parse_name($_addons, 1));
            $_controller = parse_name($_controller, 1);
        }

        $TMPL_PARSE_STRING                  = C('TMPL_PARSE_STRING');
        $TMPL_PARSE_STRING['__ADDONROOT__'] = __ROOT__ . "/Addons/{$_addons}";
        C('TMPL_PARSE_STRING', $TMPL_PARSE_STRING);

        if (!empty($_addons) && !empty($_controller) && !empty($_action)) {
            $Addons = A("Addons://{$_addons}/{$_controller}")->$_action();
        } else {
            $this->error('没有指定插件名称，控制器或操作！');
        }
    }

    /**
     * 插件后台显示页面
     * @param string $name 插件名
     * @author jry <598821125@qq.com>
     */
    public function adminList($name, $tab = 1)
    {
        // 获取插件实例
        $addon_class = get_addon_class($name);
        if (!class_exists($addon_class)) {
            $this->error('插件不存在');
        } else {
            $addon = new $addon_class();
        }

        // 自定义插件后台页面
        if ($addon->custom_adminlist) {
            $this->assign('custom_adminlist', $this->fetch($addon->custom_adminlist));
            $this->display($addon->custom_adminlist);
        } else {
            // 获取插件的$admin_list配置
            $admin_list = $addon->admin_list;
            $tab_list   = array();
            foreach ($admin_list as $key => $val) {
                $tab_list[$key]['title'] = $val['title'];
                $tab_list[$key]['href']  = U('Admin/Addon/adminList', array(
                    'name' => $name,
                    'tab'  => $key,
                ));
            }
            $admin = $admin_list[$tab];
            $param = D('Addons://' . $name . '/' . $admin['model'] . '')->adminList;
            if ($param) {
                // 搜索
                $keyword                           = (string) I('keyword');
                $condition                         = array('like', '%' . $keyword . '%');
                $map['id|' . $param['search_key']] = array(
                    $condition,
                    $condition,
                    '_multi' => true,
                );

                // 获取数据列表
                $p         = !empty($_GET["p"]) ? $_GET['p'] : 1;
                $data_list = M($param['model'])
                    ->page($p, C('ADMIN_PAGE_ROWS'))
                    ->where($map)
                    ->field(true)
                    ->order($param['order'])
                    ->select();
                $page = new Page(M($param['model'])
                        ->where($map)
                        ->count(), C('ADMIN_PAGE_ROWS'));

                // 使用Builder快速建立列表页面。
                $builder = new \Common\Builder\ListBuilder();
                $builder->setMetaTitle($addon->info['title']) // 设置页面标题
                    ->AddTopButton('addnew', array('href' => U('Admin/Addon/adminAdd', array('name' => $name, 'tab' => $tab)))) // 添加新增按钮
                    ->AddTopButton('resume', array('model' => $param['model'])) // 添加启用按钮
                    ->AddTopButton('forbid', array('model' => $param['model'])) // 添加禁用按钮
                    ->setSearch('请输入关键字', U('Admin/Addon/adminList', array('name' => $name, 'tab' => $tab)))
                    ->SetTabNav($tab_list, $tab) // 设置Tab按钮列表
                    ->setTableDataList($data_list) // 数据列表
                    ->setTableDataPage($page->show()); // 数据列表分页

                // 根据插件的list_grid设置后台列表字段信息
                foreach ($param['list_grid'] as $key => $val) {
                    $builder->addTableColumn($key, $val['title'], $val['type']);
                }

                // 根据插件的right_button设置后台列表右侧按钮
                foreach ($param['right_button'] as $key => $val) {
                    $builder->addRightButton('self', $val);
                }

                // 定义编辑按钮
                $attr          = array();
                $attr['name']  = 'edit';
                $attr['title'] = '编辑';
                $attr['class'] = 'label label-info-outline label-pill';
                $attr['href']  = U('Admin/Addon/adminEdit', array(
                    'name' => $name,
                    'tab'  => $tab,
                    'id'   => '__data_id__',
                ));

                // 显示列表
                $builder->addTableColumn('right_button', '操作', 'btn')
                    ->addRightButton('self', $attr) //添加编辑按钮
                    ->addRightButton('forbid', array('model' => $param['model'])) // 添加禁用/启用按钮
                    ->addRightButton('delete', array('model' => $param['model'])) // 添加删除按钮
                    ->display();
            } else {
                $this->error('插件列表信息不正确');
            }
        }
    }

    /**
     * 插件后台数据增加
     * @param string $name 插件名
     * @author jry <598821125@qq.com>
     */
    public function adminAdd($name, $tab)
    {
        // 获取插件实例
        $addon_class = get_addon_class($name);
        if (!class_exists($addon_class)) {
            $this->error('插件不存在');
        } else {
            $addon = new $addon_class();
        }

        // 获取插件的$admin_list配置
        $admin_list         = $addon->admin_list;
        $admin              = $admin_list[$tab];
        $addon_model_object = D('Addons://' . $name . '/' . $admin['model']);
        $param              = $addon_model_object->adminList;
        if ($param) {
            if (IS_POST) {
                $data = $addon_model_object->create();
                if ($data) {
                    $result = $addon_model_object->add($data);
                } else {
                    $this->error($addon_model_object->getError());
                }
                if ($result) {
                    $this->success('新增成功', U('Admin/Addon/adminlist', array('name' => $name, 'tab' => $tab)));
                } else {
                    $this->error('更新错误');
                }
            } else {
                // 使用FormBuilder快速建立表单页面。
                $builder = new \Common\Builder\FormBuilder();
                $builder->setMetaTitle('新增数据') //设置页面标题
                    ->setPostUrl(U('Admin/Addon/adminAdd', array('name' => $name, 'tab' => $tab))) // 设置表单提交地址
                    ->setExtraItems($param['field'])
                    ->display();
            }
        } else {
            $this->error('插件列表信息不正确');
        }
    }

    /**
     * 插件后台数据编辑
     * @param string $name 插件名
     * @author jry <598821125@qq.com>
     */
    public function adminEdit($name, $tab, $id)
    {
        // 获取插件实例
        $addon_class = get_addon_class($name);
        if (!class_exists($addon_class)) {
            $this->error('插件不存在');
        } else {
            $addon = new $addon_class();
        }

        // 获取插件的$admin_list配置
        $admin_list         = $addon->admin_list;
        $admin              = $admin_list[$tab];
        $addon_model_object = D('Addons://' . $name . '/' . $admin['model']);
        $param              = $addon_model_object->adminList;
        if ($param) {
            if (IS_POST) {
                $data = $addon_model_object->create();
                if ($data) {
                    $result = $addon_model_object->save($data);
                } else {
                    $this->error($addon_model_object->getError());
                }
                if ($result) {
                    $this->success('更新成功', U('Admin/Addon/adminlist', array('name' => $name, 'tab' => $tab)));
                } else {
                    $this->error('更新错误');
                }
            } else {
                // 使用FormBuilder快速建立表单页面。
                $builder = new \Common\Builder\FormBuilder();
                $builder->setMetaTitle('编辑数据') // 设置页面标题
                    ->setPostUrl(U('admin/addon/adminedit', array('name' => $name, 'tab' => $tab))) // 设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->setExtraItems($param['field'])
                    ->setFormData(M($param['model'])->find($id))
                    ->display();
            }
        } else {
            $this->error('插件列表信息不正确');
        }
    }
}

<?php

namespace Addons\ModelConfigEditor\Controller;

use Home\Controller\AddonController;
use Util\Tree;

class ModelConfigEditorController extends AddonController
{

    // 模块菜单列表
    public function menus()
    {
        $module_id = I('request.module_id');
        $this->assign('module_id', $module_id);
        $module_object = M('admin_module');
        $module        = $module_object->find($module_id);
        if (empty($module)) {
            $this->error('插件不存在，请重新安装');
        } else {
            $menus = $this->getMenus($module_id);
            // 转换成树状列表
            $tree      = new Tree();
            $data_list = $tree->array2tree($menus);

            // 使用Builder快速建立列表页面。
            $builder = new \Common\Builder\ListBuilder();
            $builder->setMetaTitle('添加菜单') // 设置页面标题
                ->addTopButton('addnew', ['href' => addons_url('ModelConfigEditor://ModelConfigEditor/add', ['module_id' => $module_id]),
                ]) // 添加新增按钮
            // ->addTopButton('delete') // 添加删除按钮
                ->addTableColumn('id', 'ID')
                ->addTableColumn('icon', '图标', 'icon')
                ->addTableColumn('title_show', '标题')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list) // 数据列表
                ->addRightButton('self', [
                    'href'  => addons_url('ModelConfigEditor://ModelConfigEditor/add', ['pid' => '__data_id__', 'module_id' => $module_id]),
                    'title' => '添加子菜单',
                    'class' => 'label label-primary',
                ]
                ) // 添加编辑按钮
                ->addRightButton('edit', [
                    'href' => addons_url('ModelConfigEditor://ModelConfigEditor/edit', ['id' => '__data_id__', 'module_id' => $module_id]),
                ]
                ) // 添加编辑按钮
                ->addRightButton('delete', [
                    'href' => addons_url('ModelConfigEditor://ModelConfigEditor/delete', ['id' => '__data_id__', 'module_id' => $module_id]),
                ]
                ) // 添加删除按钮
                ->display();
        }
    }

    public function add()
    {
        $module_id = I('request.module_id');
        $data      = I('post.');
        if (isset($data['icon']) && stripos($data['icon'], 'fa ') === false) {
            $data['icon'] = 'fa ' . $data['icon'];
        }
        $menus = $this->getMenus($module_id);
        if (IS_POST) {
            $ids = array_column($menus, 'id');
            asort($ids, SORT_NUMERIC);
            $next_id         = end($ids) + 1;
            $menus[$next_id] = $data;
            if (1 == $data['pid']) {
                $data['icon'] = 'fa fa-folder-open-o';
            }
            $this->saveModule($module_id, $menus);
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增链接') // 设置页面标题
                ->setPostUrl(addons_url('ModelConfigEditor://ModelConfigEditor/add', ['module_id' => $module_id])) // 设置表单提交地址
                ->addFormItem('pid', 'select', '上级链接', '上级链接', $this->list_as_tree($menus, '顶级链接'))
                ->addFormItem('title', 'text', '链接标题', '链接前台显示标题')
                ->addFormItem('url', 'text', '请填写外链URL地址', '支持http://格式或者TP的U函数解析格式')
                ->addFormItem('icon', 'icon', '图标', '链接图标')
                ->setFormData(['pid' => I('pid', 0)])
                ->display();
        }
    }

    // 编辑菜单
    public function edit()
    {
        $id        = I('id');
        $module_id = I('get.module_id');
        if (IS_POST) {
            $menus      = $this->getMenus($module_id);
            $menus[$id] = I('post.');
            if (stripos($menus[$id]['icon'], 'fa ') === false) {
                $menus[$id]['icon'] = 'fa ' . $menus[$id]['icon'];
            }
            //第一层节点 的图标固定为目录展开图标
            if (1 == $menus[$id]['pid']) {
                $menus[$id]['icon'] = 'fa fa-folder-open-o';
            }
            $this->saveModule($module_id, $menus);

        } else {
            $menus = $this->getMenus($module_id);
            $info  = $menus[$id] ?: null;

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();

            $builder->setMetaTitle('编辑链接') // 设置页面标题
                ->setPostUrl(addons_url('ModelConfigEditor://ModelConfigEditor/edit', ['id' => $id, 'module_id' => $module_id])) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('pid', 'select', '上级链接', '上级链接', $this->list_as_tree($menus, '顶级链接'))
                ->addFormItem('title', 'text', '链接标题', '链接前台显示标题')
                ->addFormItem('url', 'text', '请填写外链URL地址', '支持http://格式或者TP的U函数解析格式')
                ->addFormItem('icon', 'icon', '图标', '链接图标')
                ->setFormData($info)
                ->display();
        }
    }

    //删除菜单
    public function delete()
    {
        $id = I('request.id');
        if (1 == $id) {
            $this->error('根节点菜单不能删除');
        } else {
            $module_id = I('request.module_id');
            $menus     = $this->getMenus($module_id);
            unset($menus[$id]);
            $this->saveModule($module_id, $menus);
        }
    }

    // 保存菜单配置
    public function saveModule($id, $data)
    {
        if ($module = $this->getModule($id)) {
            // dump($module);
            $config_file = APP_PATH . $module['name'] . '/opencmf.php';
            if (is_writeable($config_file)) {
                $old_config = include $config_file;
                $new_config = array_merge($old_config, ['admin_menu' => $data]);
                if (!$new_config) {
                    $this->error('合并配置失败');
                } else {
                    $config_source   = var_export($new_config, true);
                    $config_file_str = <<<PHP
<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
// 模块信息配置
return {$config_source}
;
PHP;
                    $addon_index = addons_url('ModelConfigEditor://ModelConfigEditor/menus', ['id' => $id, 'module_id' => $module['id']]);
                    if (file_put_contents($config_file, $config_file_str)) {
                        $ret = $this->updateinfo($id);
                        if (true === $ret) {
                            // 清空所有缓存
                            $cache = \Think\Cache::getInstance();
                            $cache->clear();
                            $this->success('更新配置成功', $addon_index);
                        } else {
                            $this->error('更新失败:' . $ret);
                        }
                    } else {
                        $this->error('更新配置失败');
                    }
                }
            } else {
                $this->error("{$module['name']}下的opencmf.php 配置文件不可写");
            }
        } else {
            $this->error('保存失败，模型数据获取不到');
        }
    }

    // 获取模块名称
    public function getModuleName($id)
    {
        if (!$module = $this->getModule($id)) {
            return false;
        } else {
            return $module['name'];
        }
    }

    // 获取模块路径
    public function getModulePath($id)
    {
        if ($name = $this->getModuleName($id)) {
            return APP_PATH . $module['name'];
        } else {
            return false;
        }
    }

    // 获取全部模块
    public function getMenus($module_id)
    {
        $module      = $this->getModule($module_id);
        $module_path = APP_PATH . $module['name'];

        $config = include $module_path . '/opencmf.php';
        $menus  = $config['admin_menu'];
        foreach ($menus as $key => &$value) {
            $value['id'] = $key;
        }
        return $menus;
    }

    // 获取模块
    public function getModule($module_id)
    {
        $module_object = M('admin_module');
        $module        = $module_object->find($module_id);
        return $module;
    }

    // 更新数据库模块菜单缓存
    public function updateInfo($id)
    {
        $module_object = M('admin_module');
        $record        = $module_object->find($id);
        $name          = $record['name'];
        $config_file   = realpath(APP_PATH . $name) . '/'
        . D('Module')->install_file();
        if (!$config_file) {
            $this->error('不存在安装文件');
        }
        $config_info = include $config_file;
        $data        = $config_info['info'];

        // 读取数据库已有配置
        $db_moduel_config = $record['config'];
        $db_moduel_config = json_decode($db_moduel_config, true);

        // 处理模块配置
        if (isset($config_info['config'])) {
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
                        $config[$key] = isset($temp_arr[$key]['value']) ? $temp_arr[$key]['value'] : '';
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
        if (isset($config_info['user_nav'])) {
            $data['user_nav'] = json_encode($config_info['user_nav']);
        } else {
            $data['user_nav'] = '';
        }

        $data['id'] = $id;
        $data       = $module_object->create($data);
        if ($data) {
            $id = $module_object->save($data);
            if (false !== $id) {
                return true;
            } else {
                return '更新失败';
            }
        } else {
            return $module_object->getError();
        }
    }

    // 将树形列表转换为树
    public function list_as_tree($list, $extra = null, $key = 'id', $title_field = 'title')
    {
        //转换成树状列表(非严格模式)
        $tree = new \Util\Tree();
        $list = $tree->array2tree($list, $title_field, 'id', 'pid', 0, false);

        if ($extra) {
            $result[0] = $extra;
        }

        //转换成一维数组
        foreach ($list as $val) {
            $result[$val[$key]] = $val['title_show'];
        }
        return $result;
    }
}

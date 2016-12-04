<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Util\Tree;

/**
 * 部门控制器
 * @author jry <598821125@qq.com>
 */
class GroupController extends AdminController
{
    /**
     * 部门列表
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 搜索
        $keyword         = I('keyword', '', 'string');
        $condition       = array('like', '%' . $keyword . '%');
        $map['id|title'] = array(
            $condition,
            $condition,
            '_multi' => true,
        );

        // 获取所有部门
        $map['status'] = array('egt', '0'); //禁用和正常状态
        $data_list     = D('Group')
            ->where($map)
            ->order('sort asc, id asc')
            ->select();

        // 转换成树状列表
        $tree      = new Tree();
        $data_list = $tree->array2tree($data_list);

        $right_button['no']['title']     = '超级管理员无需操作';
        $right_button['no']['attribute'] = 'class="label label-warning" href="#"';

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('部门列表') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->addTopButton('delete') // 添加删除按钮
            ->setSearch('请输入ID/部门名称', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title_show', '标题')
            ->addTableColumn('icon', '图标', 'icon')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->alterTableData( // 修改列表数据
                array('key' => 'id', 'value' => '1'),
                array('right_button' => $right_button)
            )
            ->display();
    }

    /**
     * 新增部门
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (IS_POST) {
            $group_object       = D('Group');
            $_POST['menu_auth'] = json_encode(I('post.menu_auth'));
            $data               = $group_object->create();
            if ($data) {
                $id = $group_object->add($data);
                if ($id) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($group_object->getError());
            }
        } else {
            // 获取现有部门
            $map['status'] = array('egt', 0);
            $all_group     = select_list_as_tree('Group', $map, '顶级部门');

            // 获取功能模块的后台菜单列表
            $tree       = new Tree();
            $moule_list = D('Module')
                ->where(array('status' => 1))
                ->select(); // 获取所有安装并启用的功能模块
            $all_module_menu_list = array();
            foreach ($moule_list as $key => $val) {
                $temp                               = json_decode($val['admin_menu'], true);
                $menu_list_item                     = $tree->list2tree($temp);
                $all_module_menu_list[$val['name']] = $menu_list_item[0];
            }

            $this->assign('all_module_menu_list', $all_module_menu_list);
            $this->assign('all_group', $all_group);
            $this->assign('meta_title', '新增部门');
            $this->display('add_edit');
        }
    }

    /**
     * 编辑部门
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (IS_POST) {
            $group_object       = D('Group');
            $_POST['menu_auth'] = json_encode(I('post.menu_auth'));
            $data               = $group_object->create();
            if ($data) {
                if ($group_object->save($data) !== false) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($group_object->getError());
            }
        } else {
            // 获取部门信息
            $info              = D('Group')->find($id);
            $info['menu_auth'] = json_decode($info['menu_auth'], true);

            // 获取现有部门
            $map['status'] = array('egt', 0);
            $all_group     = select_list_as_tree('Group', $map, '顶级部门');

            // 获取所有安装并启用的功能模块
            $moule_list = D('Module')
                ->where(array('status' => 1))
                ->select();

            // 获取功能模块的后台菜单列表
            $tree                 = new Tree();
            $all_module_menu_list = array();
            foreach ($moule_list as $key => $val) {
                $temp                               = json_decode($val['admin_menu'], true);
                $menu_list_item                     = $tree->list2tree($temp);
                $all_module_menu_list[$val['name']] = $menu_list_item[0];
            }

            $this->assign('info', $info);
            $this->assign('all_module_menu_list', $all_module_menu_list);
            $this->assign('all_group', $all_group);
            $this->assign('meta_title', '编辑部门');
            $this->display('add_edit');
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
            if (in_array('1', $ids)) {
                $this->error('超级管理员组不允许操作');
            }
        } else {
            if ($ids === '1') {
                $this->error('超级管理员组不允许操作');
            }
        }
        parent::setStatus($model);
    }
}

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

/**
 * 管理员控制器
 * @author jry <598821125@qq.com>
 */
class AccessController extends AdminController
{
    /**
     * 管理员列表
     * @param $tab 配置分组ID
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 搜索
        $keyword       = I('keyword', '', 'string');
        $condition     = array('like', '%' . $keyword . '%');
        $map['id|uid'] = array(
            $condition,
            $condition,
            '_multi' => true,
        );

        // 获取所有配置
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $access_object = D('Access');
        $data_list     = $access_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('sort asc,id asc')
            ->select();
        $page = new Page(
            $access_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 设置Tab导航数据列表
        $group_object = D('Group');
        $user_object  = D('User');
        foreach ($data_list as $key => &$val) {
            $val['username']    = $user_object->getFieldById($val['uid'], 'username');
            $val['group_title'] = $group_object->getFieldById($val['group'], 'title');
        }

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('管理员列表') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->addTopButton('delete') // 添加删除按钮
            ->setSearch('请输入ID/UID', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('uid', 'UID')
            ->addTableColumn('username', '用户名')
            ->addTableColumn('group_title', '用户组')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    /**
     * 新增
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (IS_POST) {
            $access_object = D('Access');
            $data          = $access_object->create();
            if ($data) {
                if ($access_object->add($data)) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($access_object->getError());
            }
        } else {
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增配置') //设置页面标题
                ->setPostUrl(U('add')) //设置表单提交地址
                ->addFormItem('uid', 'uid', 'UID', '用户ID')
                ->addFormItem('group', 'select', '用户组', '不同用户组对应相应的权限', select_list_as_tree('Group'))
                ->display();
        }
    }

    /**
     * 编辑
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (IS_POST) {
            $access_object = D('Access');
            $data          = $access_object->create();
            if ($data) {
                if ($access_object->save($data)) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($access_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑配置') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('uid', 'uid', 'UID', '用户ID')
                ->addFormItem('group', 'select', '用户组', '不同用户组对应相应的权限', select_list_as_tree('Group'))
                ->setFormData(D('Access')->find($id))
                ->display();
        }
    }
}

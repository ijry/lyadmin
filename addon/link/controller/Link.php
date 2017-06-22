<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace addon\link\controller;

use admin\controller\Admin;
use lyf\Page;

/**
 * 友情链接
 */
class Link extends Admin
{
    /**
     * 首页
     */
    public function index($type = 1)
    {
        // 搜索
        $keyword         = I('keyword', '', 'string');
        $condition       = array('like', '%' . $keyword . '%');
        $map['id|title'] = array($condition, $condition, '_multi' => true);

        // 获取所有链接
        $p                    = !empty($_GET["p"]) ? $_GET["p"] : 1;
        $map['status']        = array('egt', '0'); // 禁用和正常状态
        $friendly_link_object = D('addon://Link/Link');
        $data_list            = $friendly_link_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('sort desc,id desc')
            ->select();
        $page = new Page(
            $friendly_link_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('友情链接列表') // 设置页面标题
            ->addTopButton('self', array( // 添加返回按钮
                'title' => '<i class="fa fa-reply"></i> 返回插件列表',
                'class' => 'btn btn-warning-outline btn-pill',
                'href'  => U('Admin/Addon/index'))
            )
            ->addTopButton('addnew', array('href' => addons_url('Link://Link/add'))) // 添加新增按钮
            ->setSearch('请输入ID/链接标题', addons_url('Link://Link/index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title', '标题')
            ->addTableColumn('type', '类型', 'callback', array($friendly_link_object, 'link_type'))
            ->addTableColumn('logo', 'Logo', 'picture')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit', array('href' => addons_url('Link://Link/edit', array('id' => '__data_id__')))) // 添加编辑按钮
            ->addRightButton('forbid', array('model' => 'addon_link', 'href' => addons_url('Link://Link/edit', array('id' => '__data_id__')))) // 添加禁用/启用按钮
            ->addRightButton('delete', array('model' => 'addon_link', 'href' => addons_url('Link://Link/edit', array('id' => '__data_id__')))) // 添加删除按钮
            ->display();
    }

    /**
     * 新增文档
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (request()->isPost()) {
            $friendly_link_object = D('addon://Link/Link');
            $data                 = $friendly_link_object->create();
            if ($data) {
                $id = $friendly_link_object->add();
                if ($id) {
                    $this->success('新增成功', addons_url('Link://Link/index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($friendly_link_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增友情链接') // 设置页面标题
                ->setPostUrl(addons_url('Link://Link/add')) // 设置表单提交地址
                ->addFormItem('title', 'text', '标题', '标题')
                ->addFormItem('logo', 'picture', 'Logo', 'Logo')
                ->addFormItem('url', 'text', '链接', '点击跳转链接')
                ->addFormItem('type', 'radio', '类型', '链接类型', array('1' => '友情链接', '2' => '合作伙伴'))
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->display();
        }
    }

    /**
     * 编辑文章
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        $friendly_link_object = D('addon://Link/Link');
        if (request()->isPost()) {
            $data = $friendly_link_object->create();
            if ($data) {
                $id = $friendly_link_object->save();
                if ($id !== false) {
                    $this->success('更新成功', addons_url('Link://Link/index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($friendly_link_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑友情链接') // 设置页面标题
                ->setPostUrl(addons_url('Link://Link/edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('title', 'text', '标题', '标题')
                ->addFormItem('logo', 'picture', 'Logo', 'Logo')
                ->addFormItem('url', 'text', '链接', '点击跳转链接')
                ->addFormItem('type', 'radio', '类型', '链接类型', array('1' => '友情链接', '2' => '合作伙伴'))
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData($friendly_link_object->find($id))
                ->display();
        }
    }
}

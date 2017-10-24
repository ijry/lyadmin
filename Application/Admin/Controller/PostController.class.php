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

use lyf\Page;

/**
 * 文章控制器
 * @author jry <598821125@qq.com>
 */
class PostController extends AdminController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 搜索
        $keyword         = I('keyword', '', 'string');
        $condition       = array('like', '%' . $keyword . '%');
        $map             = array();
        $map['id|title'] = array($condition, $condition, '_multi' => true);

        // 获取所有分类
        $p = $_GET["p"] ?: 1;
        if (I('cid')) {
            $cid = $map['cid'] = I('cid');
        }
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $post_object   = D('Admin/Post');
        $data_list     = $post_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('sort desc,id desc')
            ->select();
        $page = new Page(
            $post_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('文章列表') // 设置页面标题
            ->addTopButton('self', array( // 添加返回按钮
                'title'   => '<i class="fa fa-reply"></i> 返回导航列表',
                'class'   => 'btn btn-warning-outline btn-pill',
                'onclick' => 'javascript:history.back(-1);return false;')
            )
            ->addTopButton('addnew', array('href' => U('add', array('cid' => $cid)))) // 添加新增按钮
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->setSearch('请输入ID/标题', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('cover', '封面', 'picture')
            ->addTableColumn('title', '标题')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit', array('href' => U('edit', array('id' => '__data_id__', 'cid' => $cid)))) // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    /**
     * 新增文档
     * @author jry <598821125@qq.com>
     */
    public function add($cid)
    {
        if (request()->isPost()) {
            $post_object = D('Admin/Post');
            $data        = $post_object->create(format_data());
            if ($data) {
                $id = $post_object->add();
                if ($id) {
                    $this->success('新增成功', U('index', array('cid' => $cid)));
                } else {
                    $this->error('新增失败：' . $post_object->getError());
                }
            } else {
                $this->error($post_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增文章') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('cid', 'hidden', '分类', '分类')
                ->addFormItem('title', 'text', '标题', '标题', '', array('must' => 1))
                ->addFormItem('abstract', 'textarea', '摘要', '摘要', '', array('must' => 1))
                ->addFormItem('content', 'kindeditor', '内容', '内容', '', array('must' => 1))
                ->addFormItem('cover', 'picture', '封面', '封面', '', array('must' => 1))
                ->addFormItem('create_time', 'datetime', '发布时间', '发布时间')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(array('cid' => $cid))
                ->display();
        }
    }

    /**
     * 编辑文章
     * @author jry <598821125@qq.com>
     */
    public function edit($id, $cid)
    {
        if (request()->isPost()) {
            $post_object = D('Admin/Post');
            $data        = $post_object->create(format_data());
            if ($data) {
                $id = $post_object->save();
                if ($id !== false) {
                    $this->success('更新成功', U('index', array('cid' => $cid)));
                } else {
                    $this->error('更新失败：' . $post_object->getError());
                }
            } else {
                $this->error($post_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑文章') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('title', 'text', '标题', '标题', '', array('must' => 1))
                ->addFormItem('abstract', 'textarea', '摘要', '摘要', '', array('must' => 1))
                ->addFormItem('content', 'kindeditor', '内容', '内容', '', array('must' => 1))
                ->addFormItem('cover', 'picture', '封面', '封面', '', array('must' => 1))
                ->addFormItem('create_time', 'datetime', '发布时间', '发布时间')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(D('Admin/Post')->find($id))
                ->display();
        }
    }
}

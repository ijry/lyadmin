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

/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class FormAdmin extends AdminController
{
    /**
     * 模板自定义表单
     * @author jry <598821125@qq.com>
     */
    public function form()
    {
        $p          = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $form_model = D('Site/form');
        // 获取模板信息
        $theme_model = D('Theme');
        $theme_info  = $theme_model->find();
        if (!$theme_info) {
            $this->error('模板不存在');
        }
        $map['status'] = 1;
        $data_list     = $form_model
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            $form_model->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面
        $attr['name']    = 'edit';
        $attr['title']   = '修改表名';
        $attr['class']   = 'label label-warning-outline label-pill';
        $attr['href']    = U('form_edit', array('fid' => '__data_id__'));
        $attr1['name']   = 'edit1';
        $attr1['title']  = '编辑字段';
        $attr1['class']  = 'label label-info-outline label-pill';
        $attr1['href']   = U('Field/field', array('fid' => '__data_id__'));
        $attr2['name']   = 'edit2';
        $attr2['title']  = '查看数据';
        $attr2['class']  = 'label label-primary-outline label-pill';
        $attr2['href']   = U('Data/data', array('fid' => '__data_id__'));
        $attr4['name']   = 'edit4';
        $attr4['title']  = '生成链接';
        $attr4['class']  = 'label label-danger-outline label-pill';
        $attr4['href']   = oc_url('Site/form', array('fid' => '__data_id__'));
        $attr4['target'] = '_blank';
        $builder         = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('自定义表单') // 设置页面标题
            ->addTopButton('addnew', array('href' => U('form_add', array()))) // 添加新增按钮
        //            ->addTopButton('delete', array('model' => 'Form')) // 添加删除按钮
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title', '表单名称')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('update_time', '更新时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('self', $attr) //添加自定义按钮
            ->addRightButton('self', $attr1) //添加自定义按钮
            ->addRightButton('self', $attr2) //添加自定义按钮
            ->addRightButton('self', $attr4) //添加自定义按钮
            ->setExtraHtml('<span style="color:#DDDDDD">生成链接后，将网页上方链接复制并设定在分类模型的链接里即可使用<span>')
            ->display();

    }

    /**
     * 自定义表单修改
     * @author jry <598821125@qq.com>
     */
    public function form_edit($fid)
    {
        $index_model = D('Site/Index');
        $form_model  = D('Site/form');
        // 获取模板信息
        $theme_model = D('Theme');
        $theme_info  = $theme_model->find($info['theme']);
        if (!$theme_info) {
            $this->error('模板不存在');
        }

        // 编辑
        if (request()->isPost()) {
            $data = $form_model->create();
            if ($data) {
                $map       = array();
                $map['id'] = $fid;
                if ($form_model->where($map)->save($data)) {
                    $this->success('添加成功', U('form'));
                } else {
                    $this->error('添加失败');
                }
            } else {
                $this->error($form_model->getError());
            }
        } else {
            $map       = array();
            $map['id'] = $fid;
            $data_info = $form_model->where($map)->find();
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('表名编辑') // 设置页面标题
                ->setPostUrl(U('form_edit', array('fid' => $fid))) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('title', 'text', '表单名称', '表单名称')
                ->setFormData($data_info)
                ->display();
        }
    }

    /**
     * 模板自定义表单添加
     * @author jry <598821125@qq.com>
     */
    public function form_add()
    {
        $index_model = D('Site/Index');
        $form_model  = D('Site/form');
        // 获取模板信息
        $theme_model = D('Theme');
        $theme_info  = $theme_model->find($info['theme']);
        if (!$theme_info) {
            $this->error('模板不存在');
        }

        // 编辑
        if (request()->isPost()) {
            $data = $form_model->create();
            if ($data) {
                if ($form_model->add($data) !== false) {
                    $this->success('添加成功', U('form'));
                } else {
                    $this->error('添加失败');
                }
            } else {
                $this->error($form_model->getError());
            }
        } else {

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('表单编辑') // 设置页面标题
                ->setPostUrl(U('form_add', array())) // 设置表单提交地址
                ->addFormItem('title', 'text', '表单名称', '表单名称')
                ->display();
        }
    }

    /**
     * 模板表单数据列表
     * @author jry <598821125@qq.com>
     */
    public function data($site_id, $fid)
    {
        $p           = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $index_model = D('Sites/Index');
        $form_model  = D('Sites/form');
        $field_model = D('Sites/field');
        $data_model  = D('Sites/Data');
        //检查
        $con       = array();
        $con['id'] = $site_id;
        $info      = $index_model->where($con)->find();
        $this->assign('info', $info);
        if ($info['uid'] !== $this->is_login()) {
            $this->error('非法访问');
        }
        // 获取模板信息
        $theme_model = D('Theme');
        $theme_info  = $theme_model->find($info['theme']);
        if (!$theme_info) {
            $this->error('模板不存在');
        }
        $form_info = $form_model->where('id=' . $fid)->find();
        if (!$form_info['status']) {
            $this->error('表单数据错误');
        }
        $map           = array();
        $map['fid']    = $fid;
        $map['status'] = 1;
        $field_info    = $field_model->where($map)->select(); //取出表单字段

        $map            = array();
        $map['site_id'] = $site_id;
        $map['fid']     = $fid;
        $map['status']  = 1;
        $data_list      = $data_model
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            $data_model->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );
        $data_info = array();
        foreach ($data_list as $va) {
            $json                = json_decode($va['data'], turn);
            $json['id']          = $va['id'];
            $json['create_time'] = $va['create_time'];
            $data_info[]         = $json;
        }
        // 使用Builder快速建立列表页面
        $attr2['name']  = 'edit2';
        $attr2['title'] = '删除数据';
        $attr2['class'] = 'label label-danger-outline label-pill';
        $attr2['href']  = U('data_del', array('site_id' => $site_id, 'fid' => $fid, 'data_id' => '__data_id__'));
        $builder        = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('自定义表单') // 设置页面标题
            ->addTableColumn('id', 'ID');
        foreach ($field_info as $k1 => $v1) {
            $builder->setMetaTitle('自定义表单')
                ->addTableColumn($v1['name'], $v1['name']);
        }
        $builder->setMetaTitle('自定义表单')
            ->addTableColumn('create_time', '提交时间', 'time')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_info) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('self', $attr2) //添加自定义按钮
            ->setTemplate('Builder/list')
            ->display();
    }

    /**
     * 模板表单数据删除
     * @author jry <598821125@qq.com>
     */
    public function data_del($site_id, $fid, $data_id)
    {
        if (isset($_GET['fid'])) {
            //检查
            $index_model = D('Sites/Index');
            $con         = array();
            $con['id']   = $site_id;
            $info        = $index_model->where($con)->find();
            $this->assign('info', $info);
            if ($info['uid'] !== $this->is_login()) {
                $this->error('非法访问');
            }
            $data_model     = D('Sites/data');
            $map            = array();
            $map['id']      = $data_id;
            $map['fid']     = $fid;
            $map['site_id'] = $site_id;
            if ($data_model->where($map)->setField('status', -1)) {
                $this->success('删除成功');
            } else {
                $this->error('删除失败');
            }

        }
    }
}

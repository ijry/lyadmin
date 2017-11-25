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

/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class FieldAdmin extends AdminController
{

    /**
     * 模板表单字段
     * @author jry <598821125@qq.com>
     */
    public function field($fid)
    {
        $index_model   = D('Site/Index');
        $field_model   = D('Site/field');
        $map['fid']    = $fid;
        $map['status'] = array('egt', '0');
        $data_list     = $field_model
            ->where($map)
            ->order('id asc')
            ->select();
        // 使用Builder快速建立列表页面
        $attr1['name']  = 'edit1';
        $attr1['title'] = '编辑字段';
        $attr1['class'] = 'label label-info-outline label-pill';
        $attr1['href']  = U('field_edit', array('fid' => $fid, 'field_id' => '__data_id__'));
        $builder        = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('自定义表单') // 设置页面标题
            ->addTopButton('addnew', array('href' => U('field_add', array('fid' => $fid)))) // 添加新增按钮
            ->addTopButton('delete') // 添加删除按钮
            ->addTableColumn('id', 'ID', '', '', '4%')
            ->addTableColumn('name', '字段名称', '', '', '10%')
            ->addTableColumn('type', '表单类型', '', '', '10%')
            ->addTableColumn('title', '表单标题', '', '', '12%')
            ->addTableColumn('hint', '表单填写提示', '', '', '15%')
            ->addTableColumn('choose', '选择值', '', '', '18%')
            ->addTableColumn('status', '状态', 'status', '', '5%')
            ->addTableColumn('right_button', '操作', 'btn', '', '20%')
            ->setTableDataList($data_list) // 数据列表
            ->addRightButton('self', $attr1) //添加自定义按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') //添加自定义按钮
            ->display();
    }

    /**
     * 模板表单字段添加
     * @author jry <598821125@qq.com>
     */
    public function field_add($fid)
    {
        $index_model = D('Site/Index');
        $field_model = D('Site/field');

        // 编辑
        if (request()->isPost()) {
            $data = $field_model->create();
            if ($data) {
                if ($field_model->add($data) !== false) {
                    $this->success('添加成功', U('field', array('fid' => $fid)));
                } else {
                    $this->error('添加失败');
                }
            } else {
                $this->error($field_model->getError());
            }
        } else {

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('字段编辑') // 设置页面标题
                ->setPostUrl(U('field_add', array('fid' => $fid))) // 设置表单提交地址
                ->addFormItem('fid', 'hidden', 'ID', 'ID')
                ->addFormItem('name', 'text', '字段名称', '表单名称')
                ->addFormItem('type', 'select', '字段类型', '字段类型', $field_model->field_list())
                ->addFormItem('title', 'text', '表单标题', '表单标题')
                ->addFormItem('hint', 'text', '表单填写提示', '表单填写提示')
                ->addFormItem('choose', 'text', '设置选择多选单选时用来选择的值', '选择值之间用"/"隔开,列：男/女/保密')
                ->setFormData(array('fid' => $fid))
                ->display();
        }

    }

    /**
     * 模板表单字段编辑
     * @author jry <598821125@qq.com>
     */
    public function field_edit($fid, $field_id)
    {
        $field_model = D('Site/field');
        // 编辑
        if (request()->isPost()) {
            $data = $field_model->create();
            if ($data) {
                $map['fid'] = $fid;
                $map['id']  = $field_id;
                if ($field_model->where($map)->save($data) !== false) {
                    $this->success('修改成功', U('field', array('fid' => $fid)));
                } else {
                    $this->error('修改失败');
                }
            } else {
                $this->error($field_model->getError());
            }
        } else {
            $map['fid'] = $fid;
            $map['id']  = $field_id;
            $data_list  = $field_model
                ->where($map)
                ->find();
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('字段编辑') // 设置页面标题
                ->setPostUrl(U('field_edit', array('fid' => $fid, 'field_id' => $field_id))) // 设置表单提交地址
                ->addFormItem('fid', 'hidden', 'ID', 'ID')
                ->addFormItem('name', 'text', '字段名称', '表单名称')
                ->addFormItem('type', 'select', '字段类型', '字段类型', $field_model->field_list())
                ->addFormItem('title', 'text', '表单标题', '表单标题')
                ->addFormItem('hint', 'text', '表单填写提示', '表单填写提示')
                ->addFormItem('choose', 'text', '设置选择多选单选时用来选择的值', '选择值之间用"/"隔开,列：男/女/保密')
                ->setFormData($data_list)
                ->display();
        }

    }

}

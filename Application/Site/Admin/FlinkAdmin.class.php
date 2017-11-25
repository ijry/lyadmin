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
 * 友情链接控制器
 * @author jry <598821125@qq.com>
 */
class FlinkAdmin extends AdminController
{
    /**
     * 友情链接列表
     * @author jry <598821125@qq.com>
     */
    public function flink()
    {
        // 获取所有链接
        $map           = array();
        $map['status'] = array('egt', '0');
        $data_list     = D('Site/Flink')->where($map)->order('sort asc,id asc')->select();
        // 转换成树状列表
        $tree      = new \lyf\Tree();
        $data_list = $tree->array2tree($data_list);
        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('友情链接') // 设置页面标题
            ->addTopButton('addnew', array('href' => U('flink_add'))) // 添加新增按钮
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title_show', '友链名称')
            ->addTableColumn('cover', '友链logo', 'picture')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->addRightButton('edit', array('href' => U('flink_edit', array('id' => '__data_id__')))) // 添加编辑按钮
            ->addRightButton('forbid', array('model' => 'Flink')) // 添加禁用/启用按钮
            ->addRightButton('delete', array('model' => 'Flink')) // 添加删除按钮
            ->display();
    }
    /**
     * 新增友链
     * @author jry <598821125@qq.com>
     */
    public function flink_add()
    {
        // 新增
        $flink_object = D('Site/Flink');
        if (request()->isPost()) {
            $data = $flink_object->create();
            if ($data) {
                $id = $flink_object->add();
                if ($id) {
                    $this->success('新增成功', U('flink'));
                } else {
                    $this->error('新增失败' . $flink_object->getError());
                }
            } else {
                $this->error($flink_object->getError());
            }
        } else {

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增') // 设置页面标题
                ->setPostUrl(U('flink_add')) // 设置表单提交地址
                ->addFormItem('pid', 'select', '上级', '所属的上级', select_list_as_tree('Site/Flink', array(), '顶级'))
                ->addFormItem('title', 'text', '友链名称', '分类标题', '', array('must' => 1))
                ->addFormItem('logo', 'picture_temp', '友链logo', '友链logo', '', array('self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('url', 'text', '友链url', '友链url')
                ->display();
        }
    }
    /**
     * 编辑友链
     * @author jry <598821125@qq.com>
     */
    public function flink_edit($id)
    {

        $flink_object = D('Site/Flink');
        $flink_info   = $flink_object->find($id);
        if (!$flink_info) {
            $this->error('友链不存在');
        }
        // 编辑
        if (request()->isPost()) {
            $data = $flink_object->create();
            if ($data) {
                if ($flink_object->save() !== false) {
                    $this->success('更新成功', U('flink'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($flink_object->getError());
            }
        } else {

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
                ->setPostUrl(U('flink_edit', array('id' => $id))) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('pid', 'select', '上级', '所属的上级', select_list_as_tree('Site/Flink', array(), '顶级'))
                ->addFormItem('title', 'text', '友链名称', '分类标题', '', array('must' => 1))
                ->addFormItem('logo', 'picture_temp', '友链logo', '友链logo', '', array('self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('url', 'text', '友链url', '友链url')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData($flink_info)
                ->display();
        }
    }
}

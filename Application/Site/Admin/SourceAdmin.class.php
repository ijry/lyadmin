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

class SourceAdmin extends AdminController
{
    /**
     * 来源列表
     */
    public function source(){
        $map           = array();
        $map['status'] = array('egt', '0');
        $data_list     = D('Site/Source')->where($map)->order('sort asc,id asc')->select();

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('来源管理') // 设置页面标题
        ->addTopButton('addnew', array('href' => U('source_add', array()))) // 添加新增按钮
        ->addTableColumn('id', 'ID')
            ->addTableColumn('source', '来源')
            ->addTableColumn('create_time','创建时间','time')
            ->addTableColumn('sort', '排序', 'quickedit')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->addRightButton('edit', array('href' => U('source_edit', array('id' => '__data_id__')))) // 添加编辑按钮
            ->addRightButton('forbid', array('model' => 'Source')) // 添加禁用/启用按钮
            ->addRightButton('delete', array('model' => 'Source')) // 添加删除按钮
            ->display();
    }

    /**
     * 新增来源
     */
    public function source_add(){
        if (request()->isPost()) {
            $model_object = D("Source");
            $data         = $model_object->create(format_data());
            if ($data) {
                $id = $model_object->add($data);
                if ($id) {
                    $this->success("新增成功", U("source"));
                } else {
                    $this->error("新增失败" . $model_object->getError());
                }
            } else {
                $this->error($model_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle("新增来源") // 设置页面标题
            ->setPostUrl(U("source_add")) // 设置表单提交地址
            ->addFormItem("source", "text", "文章来源", "用于文章来源的名称")
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->display();
        }
    }

    /**
     * 编辑来源
     */
    public function source_edit($id){
        if (request()->isPost()) {
            $model_object = D("Source");
            $data         = $model_object->create(format_data());
            if ($data) {
                $id = $model_object->save($data);
                if ($id !== false) {
                    $this->success("更新成功", U("source"));
                } else {
                    $this->error("更新失败" . $model_object->getError());
                }
            } else {
                $this->error($model_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle("编辑来源") // 设置页面标题
            ->setPostUrl(U("source_edit")) // 设置表单提交地址
            ->addFormItem("id", "hidden", "ID", "ID")
                ->addFormItem("source", "text", "文章来源", "用于显示文章来源的名字")
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(D("Source")->find($id))
                ->display();
        }
    }
}
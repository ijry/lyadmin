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
 * 幻灯片控制器
 * @author jry <598821125@qq.com>
 */
class SliderAdmin extends AdminController
{
    /**
     * 幻灯列表
     * @author jry <598821125@qq.com>
     */
    public function slider()
    {
        // 获取所有幻灯
        $map           = array();
        $map['status'] = array('egt', '0');
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $slider_model  = D('Site/Slider');
        $data_list     = $slider_model->where($map)->order('sort desc,id desc')->page($p, 10)->select();
        // 分页
        $page = new \lyf\Page(
            $slider_model->where($map)->count(),
            10
        );
        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('幻灯列表') // 设置页面标题
            ->addTopButton('addnew', array('href' => U('slider_add'))) // 添加新增按钮
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title', '标题')
            ->addTableColumn('cover', '图片', 'picture_temp', '', array('self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show())
            ->addRightButton('edit', array('href' => U('slider_edit', array('id' => '__data_id__')))) // 添加编辑按钮
            ->addRightButton('forbid', array('model' => 'Slider')) // 添加禁用/启用按钮
            ->addRightButton('delete', array('model' => 'Slider')) // 添加删除按钮
            ->display();
    }

    /**
     * 新增幻灯
     * @author jry <598821125@qq.com>
     */
    public function slider_add()
    {
        // 新增
        $slider_model = D('Site/Slider');
        if (request()->isPost()) {
            $data = $slider_model->create();
            if ($data) {
                $id = $slider_model->add();
                if ($id) {
                    $this->success('新增成功', U('slider'));
                } else {
                    $this->error('新增失败' . $slider_model->getError());
                }
            } else {
                $this->error($slider_model->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增幻灯') // 设置页面标题
                ->setPostUrl(U('slider_add')) // 设置表单提交地址
                ->addFormItem('title', 'text', '标题', '标题', '', array('must' => 1))
                ->addFormItem('cover', 'picture_temp', '封面', '封面', null, array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('url', 'text', '跳转地址', '跳转地址')
                ->addFormItem('target', 'radio', '打开方式', '打开方式', array('' => '当前窗口', '_blank' => '新窗口打开'))
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->display();
        }
    }

    /**
     * 编辑幻灯
     * @author jry <598821125@qq.com>
     */
    public function slider_edit($id)
    {
        // 权限检测
        $slider_model = D('Site/Slider');
        $slider_info  = $slider_model->find($id);
        if (!$slider_info) {
            $this->error('幻灯不存在');
        }
        // 编辑
        if (request()->isPost()) {
            $data = $slider_model->create();
            if ($data) {
                if ($slider_model->save() !== false) {
                    $this->success('更新成功', U('slider'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($slider_model->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑幻灯') // 设置页面标题
                ->setPostUrl(U('slider_edit', array('id' => $id))) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('title', 'text', '标题', '标题', '', array('must' => 1))
                ->addFormItem('cover', 'picture_temp', '封面', '封面', null, array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('url', 'text', '跳转地址', '跳转地址')
                ->addFormItem('target', 'radio', '打开方式', '打开方式', array('' => '当前窗口', '_blank' => '新窗口打开'))
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData($slider_info)
                ->display();
        }
    }
}

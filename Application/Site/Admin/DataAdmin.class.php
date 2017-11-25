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
class DataAdmin extends AdminController
{
    /**
     * 模板表单数据列表
     * @author jry <598821125@qq.com>
     */
    public function data($fid)
    {
        $p           = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $form_model  = D('Site/form');
        $field_model = D('Site/field');
        $data_model  = D('Site/Data');
        //检查
        $form_info = $form_model->where('id=' . $fid)->find();
        if (!$form_info['status']) {
            $this->error('表单数据错误');
        }
        $map           = array();
        $map['fid']    = $fid;
        $map['status'] = 1;
        $field_info    = $field_model->where($map)->select(); //取出表单字段

        $map           = array();
        $map['fid']    = $fid;
        $map['status'] = 1;
        $data_list     = $data_model
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
            ->addRightButton('delete') //添加自定义按钮
            ->display();
    }

}

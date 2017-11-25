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
class LiuyanAdmin extends AdminController
{
    public function liuyan()
    {
        // 获取所有留言
        $map           = array();
        $map['status'] = array('egt', '0');
        $data_list     = D('Site/Liuyan')->where($map)->order('id desc')->select();
        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('留言列表') // 设置页面标题
            ->addTableColumn('id', 'ID')
            ->addTableColumn('name', '姓名')
            ->addTableColumn('email', '邮箱')
            ->addTableColumn('phone', '电话')
            ->addTableColumn('content', '内容')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }
}

<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Home\Controller;

/**
 * 前台默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexController extends HomeController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        $this->assign('meta_title', "首页");
        $this->display();
    }

    /**
     * 系统配置
     * @author jry <598821125@qq.com>
     */
    public function config($name = '')
    {
        $data_list = C($name);
        $this->assign('data_list', $data_list);
        $this->assign('meta_title', '系统配置');
        $this->display();
    }

    /**
     * 导航
     * @author jry <598821125@qq.com>
     */
    public function nav($group = 'bottom')
    {
        $data_list = D('Admin/Nav')->getNavTree(0, $group);
        $this->assign('data_list', $data_list);
        $this->assign('meta_title', '导航列表');
        $this->display();
    }

    /**
     * 模块
     * @author jry <598821125@qq.com>
     */
    public function module()
    {
        $map['status'] = 1;
        $data_list     = D('Admin/MODULE')->where($map)->select();
        $this->assign('data_list', $data_list);
        $this->assign('meta_title', '模块列表');
        $this->display();
    }

    /**
     * 发现页面
     * 主要是移动端使用
     * @author jry <598821125@qq.com>
     */
    public function find()
    {
        $data_list = D('Admin/Nav')->getNavTree(0, 'find');
        $this->assign('data_list', $data_list);
        $this->assign('meta_title', '发现');
        $this->display();
    }
}

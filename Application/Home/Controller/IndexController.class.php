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
}

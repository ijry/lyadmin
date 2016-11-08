<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Home\Controller;

use Think\Controller;

/**
 * 跳转到后台控制器
 * @author jry <598821125@qq.com>
 */
class AdminController extends Controller
{
    /**
     * 自动跳转到后台入口文件
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        redirect(C('HOME_PAGE') . '/admin.php');
    }
}

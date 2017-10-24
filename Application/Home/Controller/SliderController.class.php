<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;

use Home\Controller\HomeController;

/**
 * 幻灯片控制器
 * @author jry <598821125@qq.com>
 */
class SliderController extends HomeController
{
    /**
     * 幻灯片列表
     * @author jry <598821125@qq.com>
     */
    public function index($limit = 5, $page = 1, $order = 'sort desc,id desc')
    {
        $map['status'] = 1;
        $list          = D("Admin/Slider")->getList($limit, $page, $order, $map);
        $this->success('幻灯片列表', '', array('data' => $list));
    }
}

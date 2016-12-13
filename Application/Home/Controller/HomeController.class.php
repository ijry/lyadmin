<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Home\Controller;

use Common\Controller\ControllerController;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用模块名
 * @author jry <598821125@qq.com>
 */
class HomeController extends ControllerController
{
    /**
     * 用户信息
     * @author jry <598821125@qq.com>
     */
    protected $user_info;

    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize()
    {
        // 系统开关
        if (!C('TOGGLE_WEB_SITE')) {
            $this->error('站点已经关闭，请稍后访问~');
        }

        // 监听行为扩展
        try {
            \Think\Hook::listen('corethink_behavior');
        } catch (\Exception $e) {
            file_put_contents(RUNTIME_PATH . 'error.json', json_encode($e->getMessage()));
        }

        // 记录当前url
        if (MODULE_NAME !== 'User' && IS_GET === true) {
            cookie('forward', (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]);
        }
    }

    /**
     * 用户登录检测
     * @author jry <598821125@qq.com>
     */
    protected function is_login()
    {
        //用户登录检测
        $uid = is_login();
        if ($uid) {
            return $uid;
        } else {
            if (IS_AJAX) {
                $this->error('请先登录系统', U('User/User/login', '', true, true), array('login' => 1));
            } else {
                redirect(U('User/User/login', '', true, true));
            }
        }
    }

    /**
     * 用户VIP权限检测
     * @author jry <598821125@qq.com>
     */
    protected function is_vip($level = 1)
    {
        if (is_dir('./Application/Vip/')) {
            $vip      = is_vip();
            $vip_info = D('Vip/Index')->find($vip);
            if ($vip && $vip_info['type_info']['level'] >= $level) {
                return $vip;
            } else {
                $con['status'] = 1;
                $con['level']  = $level;
                $need_vip_info = D('Vip/Type')->where($con)->find();
                $this->error('请先开通' . $need_vip_info['title'] . 'VIP', U('Vip/Index/index', '', true, true));
            }
        }
    }

    /**
     * 是否实名认证
     * @author jry <598821125@qq.com>
     */
    protected function is_cert()
    {
        $user_info = $this->user_info;
        if ($user_info['cert_info']) {
            return $user_info['id'];
        } else {
            if (IS_AJAX) {
                $this->error('请先实名认证', U('User/Cert/index', '', true, true));
            } else {
                redirect(U('User/Cert/index', '', true, true));
            }
        }
    }
}

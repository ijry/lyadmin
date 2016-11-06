<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Common\Controller\ControllerController;
use Think\Verify;

/**
 * 后台唯一不需要权限验证的控制器
 * @author jry <598821125@qq.com>
 */
class PublicController extends ControllerController
{
    /**
     * 后台登陆
     * @author jry <598821125@qq.com>
     */
    public function login()
    {
        if (IS_POST) {
            $username = I('username');
            $password = I('password');

            // 图片验证码校验
            if (!$this->check_verify(I('post.verify')) && 'localhost' !== $_SERVER['HTTP_HOST'] && '127.0.0.1' !== $_SERVER['HTTP_HOST']) {
                //$this->error('验证码输入错误！');
            }

            // 验证用户名密码是否正确
            $user_object = D('Admin/User');
            $user_info   = $user_object->login($username, $password);
            if (!$user_info) {
                $this->error($user_object->getError());
            }

            // 验证管理员表里是否有该用户
            $account_object = D('Admin/Access');
            $where['uid']   = $user_info['id'];
            $account_info   = $account_object->where($where)->find();
            if (!$account_info) {
                $this->error('该用户没有管理员权限' . $account_object->getError());
            }

            // 设置登录状态
            $uid = $user_object->auto_login($user_info);

            // 跳转
            if (0 < $account_info['uid'] && $account_info['uid'] === $uid) {
                $this->success('登录成功！', U('Admin/Index/index'));
            } else {
                $this->logout();
            }
        } else {
            $this->assign('meta_title', '管理员登录');
            $this->display();
        }
    }

    /**
     * 注销
     * @author jry <598821125@qq.com>
     */
    public function logout()
    {
        session('user_auth', null);
        session('user_auth_sign', null);
        session('user_group', null);
        $this->success('退出成功！', U('login'));
    }

    /**
     * 图片验证码生成，用于登录和注册
     * @author jry <598821125@qq.com>
     */
    public function verify($vid = 1)
    {
        $verify         = new Verify();
        $verify->length = 4;
        $verify->entry($vid);
    }

    /**
     * 检测验证码
     * @param  integer $id 验证码ID
     * @return boolean 检测结果
     */
    public function check_verify($code, $vid = 1)
    {
        $verify = new Verify();
        return $verify->check($code, $vid);
    }
}

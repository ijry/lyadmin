<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;

/**
 * 系统升级控制器
 * @author jry <598821125@qq.com>
 */
class UpdateController extends AdminController
{
    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize()
    {
        //只有ID为1的超级管理员才有权限系统更新
        if (session('user_auth.uid') !== '1') {
            $this->success('');
        }
    }

    /**
     * 检查新版本
     * @author jry <598821125@qq.com>
     */
    public function checkVersion()
    {
        //远程更新地址
        $url = 'http://www.lingyun.net' . C('UPDATE_URL') . '.html?action=check';

        //参数设置
        $params = array(
            //系统信息
            'product_name'    => C('PRODUCT_NAME'),
            'current_version' => C('CURRENT_VERSION'),
            'company_name'    => C('COMPANY_NAME'),
            'develop_version' => C('DEVELOP_VERSION'),
            'build_version'   => C('BUILD_VERSION'),
            'product_model'   => C('PRODUCT_MODEL'),
            'product_title'   => C('PRODUCT_TITLE'),
            'develop_team'    => C('DEVELOP_TEAM'),

            //用户信息
            'data_auth_key'   => sha1(C('DATA_AUTH_KEY')),
            'website_domain'  => $_SERVER['HTTP_HOST'],
            'server_software' => php_uname() . '_' . $_SERVER['SERVER_SOFTWARE'],
            'website_title'   => C('WEB_SITE_TITLE'),
            'auth_sn'         => C('AUTH_SN'),
            'autn_username'   => C('AUTH_USERNAME'),
            'autn_password'   => C('AUTH_PASSWORD'),
        );
        $vars = http_build_query($params);

        //获取版本数据
        $conf_arr = array(
            'post' => $params,
        );
        $result = json_decode(\Util\Http::fsockopenDownload($url, $conf_arr), true);

        if ($result['status'] == 1) {
            $this->ajaxReturn($result);
        } else {
            $this->error('连接服务器失败');
        }
    }
}

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
/**
 * 安装程序配置文件
 */
return array(
    //产品配置
    'install_product_name'   => 'lyadmin2.0', //产品名称
    'install_website_domain' => 'http://www.lingyun.net', //官方网址
    'install_company_name'   => '南京科斯克网络科技有限公司', //公司名称
    'original_table_prefix'  => 'ly_', //默认表前缀

    //模板相关配置
    'view_replace_str'       => array(
        '__PUBLIC__' => __ROOT__ . '/public',
        '__LYUI__'   => __ROOT__ . '/public/libs/lyui/dist',
        '__IMG__'    => __ROOT__ . '/application/install/view/public/img',
        '__CSS__'    => __ROOT__ . '/application/install/view/public/css',
        '__JS__'     => __ROOT__ . '/application/install/view/public/js',
    ),

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => ENV_PRE . 'install_',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => ENV_PRE . 'install_',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    // 默认模块
    'default_module'         => 'install',
);

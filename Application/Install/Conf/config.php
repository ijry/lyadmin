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
$_config = array(
    //产品配置
    'INSTALL_PRODUCT_NAME'   => '零云', //产品名称
    'INSTALL_WEBSITE_DOMAIN' => 'http://www.lingyun.net', //官方网址
    'INSTALL_COMPANY_NAME'   => '南京科斯克网络科技有限公司', //公司名称
    'ORIGINAL_TABLE_PREFIX'  => 'ly_', //默认表前缀

    //模板相关配置
    'TMPL_PARSE_STRING'      => array(
        '__PUBLIC__' => __ROOT__ . '/Public',
        '__LYUI__'   => __ROOT__ . '/Public/libs/lyui/dist',
        '__IMG__'    => __ROOT__ . '/Application/' . request()->module() . '/View/Public/img',
        '__CSS__'    => __ROOT__ . '/Application/' . request()->module() . '/View/Public/css',
        '__JS__'     => __ROOT__ . '/Application/' . request()->module() . '/View/Public/js',
    ),

    // Session支持
    'SESSION_OPTIONS'        => array(
        'type' => '',
    ),

    //前缀设置避免冲突
    'DATA_CACHE_PREFIX'      => ENV_PRE . request()->module() . '_', //缓存前缀
    'SESSION_PREFIX'         => ENV_PRE . request()->module() . '_', //Session前缀
    'COOKIE_PREFIX'          => ENV_PRE . request()->module() . '_', //Cookie前缀

    //是否开启模板编译缓存,设为false则每次都会重新编译
    'TMPL_CACHE_ON'          => false,

    // 默认模块
    'DEFAULT_MODULE'         => 'Install',
);

// 额外配置
$extra_config = array();
if (is_file('./Data/extra.php')) {
    $extra_config = include './Data/extra.php'; // 包含数据库连接配置
}

// 返回合并的配置
return array_merge(
    $_config, // 系统全局默认配置
    $extra_config // 额外配置
);

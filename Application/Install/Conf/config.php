<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
/**
 * 安装程序配置文件
 */
return array(
    //产品配置
    'INSTALL_PRODUCT_NAME'   => 'lyadmin', //产品名称
    'INSTALL_WEBSITE_DOMAIN' => 'http://lyadmin.lyunweb.com', //官方网址
    'INSTALL_COMPANY_NAME'   => '南京科斯克网络科技有限公司', //公司名称
    'ORIGINAL_TABLE_PREFIX'  => 'ly_', //默认表前缀

    //模板相关配置
    'TMPL_PARSE_STRING'      => array(
        '__PUBLIC__' => __ROOT__ . '/Public',
        '__LYUI__'   => __ROOT__ . '/Public/libs/lyui/dist',
        '__IMG__'    => __ROOT__ . '/Application/' . MODULE_NAME . '/View/Public/img',
        '__CSS__'    => __ROOT__ . '/Application/' . MODULE_NAME . '/View/Public/css',
        '__JS__'     => __ROOT__ . '/Application/' . MODULE_NAME . '/View/Public/js',
    ),

    //前缀设置避免冲突
    'DATA_CACHE_PREFIX'      => ENV_PRE . MODULE_NAME . '_', //缓存前缀
    'SESSION_PREFIX'         => ENV_PRE . MODULE_NAME . '_', //Session前缀
    'COOKIE_PREFIX'          => ENV_PRE . MODULE_NAME . '_', //Cookie前缀

    //是否开启模板编译缓存,设为false则每次都会重新编译
    'TMPL_CACHE_ON'          => false,

    // 默认模块
    'DEFAULT_MODULE'         => 'Install',
);

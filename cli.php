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
 * 此入口主要是给cli模式使用的
 * 注意cli模式只能在数据库是非Saas模式下运行
 */
ignore_user_abort(true);
set_time_limit(0);

/**
 * Content-type设置
 */
header("Content-type: text/html; charset=utf-8");

/**
 * PHP版本检查
 */
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    die('require PHP > 5.4.0 !');
}

/**
 * PHP报错设置
 */
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

/**
 * 开发模式环境变量前缀
 */
define('ENV_PRE', 'LY_');

/**
 * 定义前台标记
 */
define('MODULE_MARK', 'Home');

/**
 * 程序目录设置
 * CLI模式下必须使用绝对地址
 */
define('__ROOT__', dirname( __FILE__));

/**
 * 应用目录设置
 * 安全期间，建议安装调试完成后移动到非WEB目录
 */
define('APP_PATH', __ROOT__ . '/Application/');
define('APP_DIR', __ROOT__ . '/Application/');
define('BUILDER_DIR', APP_DIR . 'Common/util/lyf/builder/');

/**
 * 采用CLI运行模式运行
 */
define('APP_MODE','cli');

/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define('RUNTIME_PATH', './Runtime/');

/**
 * 静态缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define('HTML_PATH', RUNTIME_PATH . 'Html/');

/**
 * 禁止修改超级管理员密码
 */
define('FORBID_EDIT_ADMIN_PWD', false);

/**
 * 包含开发模式数据库连接配置
 */
if (@$_SERVER[ENV_PRE . 'DEV_MODE'] !== 'true') {
    @include './Data/dev.php';
}

/**
 * 系统调试设置, 项目正式部署后请设置为false
 */
if ($_SERVER[ENV_PRE . 'APP_DEBUG'] === 'false') {
    define('APP_DEBUG', false);
} elseif ($_SERVER[ENV_PRE . 'APP_DEBUG'] === 'true') {
    define('APP_DEBUG', true);
} else {
    define('APP_DEBUG', true);
}

// 演示模式
define('APP_DEMO', false);

/**
 * 系统安装及开发模式检测
 */
if (is_file('./Data/install.lock') === false && @$_SERVER[ENV_PRE . 'DEV_MODE'] !== 'true') {
    define('BIND_MODULE', 'Install');
}

/**
 * Composer
 */
if (is_file('./vendor/autoload.php')) {
    require './vendor/autoload.php';
}

/**
 * 引入核心入口
 */
require './Framework/Lingyun.php';

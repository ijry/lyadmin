<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------

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
 * 开发模式环境变量前缀
 */
define('ENV_PRE', 'LY_');

/**
 * 定义后台标记
 */
define('MODULE_MARK', 'Admin');

/**
 * 演示模式
 */
if (ISSET($_SERVER[ENV_PRE . 'APP_DEMO']) && $_SERVER[ENV_PRE . 'APP_DEMO'] === 'true') {
    define('APP_DEMO', true);
} else {
    define('APP_DEMO', false);
}

/**
 * 包含开发模式数据库连接配置
 */
if (@$_SERVER[ENV_PRE . 'DEV_MODE'] !== 'true') {
    @include __DIR__ . '/data/dev.php';
}

// 加载框架引导文件
require __DIR__ . '/lyf.php';

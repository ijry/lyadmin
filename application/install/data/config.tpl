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
 * 数据库连接配置文件

 */

// 开启开发部署模式
if (@$_SERVER[ENV_PRE.'DEV_MODE'] === 'true') {
    // 数据库配置
    return array(
        'database' => [
            'type'   => $_SERVER[ENV_PRE.'DB_TYPE'] ? : '[DB_TYPE]',       // 数据库类型
            'hostname'   => $_SERVER[ENV_PRE.'DB_HOST'] ? : '[DB_HOST]',       // 服务器地址
            'database'   => $_SERVER[ENV_PRE.'DB_NAME'] ? : '[DB_NAME]',       // 数据库名
            'username'   => $_SERVER[ENV_PRE.'DB_USER'] ? : '[DB_USER]',       // 用户名
            'password'    => $_SERVER[ENV_PRE.'DB_PWD']  ? : '[DB_PWD]',        // 密码
            'hostport'   => $_SERVER[ENV_PRE.'DB_PORT'] ? : '[DB_PORT]',       // 端口
            'prefix' => $_SERVER[ENV_PRE.'DB_PREFIX'] ? : '[DB_PREFIX]',   // 数据库表前缀
        ],
        'auth_key'  => '[AUTH_KEY]', // 系统加密KEY，轻易不要修改此项，否则容易造成用户无法登录，如要修改，务必备份原key
    );
} else {
    // 数据库配置
    return array(
        'database' => [
            'type'   => '[DB_TYPE]',       // 数据库类型
            'hostname'   => '[DB_HOST]',       // 服务器地址
            'database'   => '[DB_NAME]',       // 数据库名
            'username'   => '[DB_USER]',       // 用户名
            'password'    => '[DB_PWD]',        // 密码
            'hostport'   => '[DB_PORT]',       // 端口
            'prefix' => '[DB_PREFIX]',     // 数据库表前缀
        ],
        'auth_key'  => '[AUTH_KEY]', // 系统加密KEY，轻易不要修改此项，否则容易造成用户无法登录，如要修改，务必备份原key
    );
}

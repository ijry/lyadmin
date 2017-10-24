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
 * 系统环境检测
 * @return array 系统环境数据
 * @author jry <598821125@qq.com>
 */
function check_env()
{
    $items = array(
        'os'     => array(
            'title'   => '操作系统',
            'limit'   => '不限制',
            'current' => PHP_OS,
            'icon'    => 'fa-check text-success',
        ),
        'php'    => array(
            'title'   => 'PHP版本',
            'limit'   => '5.4+',
            'current' => PHP_VERSION,
            'icon'    => 'fa-check text-success',
        ),
        'upload' => array(
            'title'   => '附件上传',
            'limit'   => '不限制',
            'current' => ini_get('file_uploads') ? ini_get('upload_max_filesize') : '未知',
            'icon'    => 'fa-check text-success',
        ),
        'gd'     => array(
            'title'   => 'GD库',
            'limit'   => '2.0+',
            'current' => '未知',
            'icon'    => 'fa-check text-success',
        ),
        'disk'   => array(
            'title'   => '磁盘空间',
            'limit'   => '200M+',
            'current' => '未知',
            'icon'    => 'fa-check text-success',
        ),
    );

    //PHP环境检测
    if ($items['php']['current'] < 5.4) {
        $items['php']['icon'] = 'fa-remove text-danger';
        session('error', true);
    }

    //GD库检测
    $tmp = function_exists('gd_info') ? gd_info() : array();
    if (!$tmp['GD Version']) {
        $items['gd']['current'] = '未安装';
        $items['gd']['icon']    = 'fa-remove text-danger';
        session('error', true);
    } else {
        $items['gd']['current'] = $tmp['GD Version'];
    }
    unset($tmp);

    //磁盘空间检测
    if (function_exists('disk_free_space')) {
        $disk_size                = floor(disk_free_space('./') / (1024 * 1024)) . 'M';
        $items['disk']['current'] = $disk_size . 'MB';
        if ($disk_size < 200) {
            $items['disk']['icon'] = 'fa-remove text-danger';
            session('error', true);
        }
    }

    return $items;
}

/**
 * 目录，文件读写检测
 * @return array 检测数据
 * @author jry <598821125@qq.com>
 */
function check_dirfile()
{
    $items = array(
        '0' => array(
            'type'  => 'dir',
            'path'  => RUNTIME_PATH,
            'title' => '可写',
            'icon'  => 'fa-check text-success',
        ),
        '1' => array(
            'type'  => 'dir',
            'path'  => './Uploads',
            'title' => '可写',
            'icon'  => 'fa-check text-success',
        ),
        '2' => array(
            'type'  => 'dir',
            'path'  => './Data',
            'title' => '可写',
            'icon'  => 'fa-check text-success',
        ),
    );

    foreach ($items as &$val) {
        $path = $val['path'];
        if ('dir' === $val['type']) {
            if (!is_writable($path)) {
                if (is_dir($path)) {
                    $val['title'] = '不可写';
                    $val['icon']  = 'fa-remove text-danger';
                    session('error', true);
                } else {
                    $val['title'] = '不存在';
                    $val['icon']  = 'fa-remove text-danger';
                    session('error', true);
                }
            }
        } else {
            if (file_exists($path)) {
                if (!is_writable($path)) {
                    $val['title'] = '不可写';
                    $val['icon']  = 'fa-remove text-danger';
                    session('error', true);
                }
            } else {
                if (!is_writable(dirname($path))) {
                    $val['title'] = '不存在';
                    $val['icon']  = 'fa-remove text-danger';
                    session('error', true);
                }
            }
        }
    }
    return $items;
}

/**
 * 函数检测
 * @return array 检测数据
 */
function check_func_and_ext()
{
    $items = array(
        '0' => array(
            'type'    => 'ext',
            'name'    => 'pdo',
            'title'   => '支持',
            'current' => extension_loaded('pdo'),
            'icon'    => 'fa-check text-success',
        ),
        '1' => array(
            'type'    => 'ext',
            'name'    => 'pdo_mysql',
            'title'   => '支持',
            'current' => extension_loaded('pdo_mysql'),
            'icon'    => 'fa-check text-success',
        ),
        '2' => array(
            'type'  => 'func',
            'name'  => 'file_get_contents',
            'title' => '支持',
            'icon'  => 'fa-check text-success',
        ),
        '3' => array(
            'type'  => 'func',
            'name'  => 'mb_strlen',
            'title' => '支持',
            'icon'  => 'fa-check text-success',
        ),
    );
    foreach ($items as &$val) {
        switch ($val['type']) {
            case 'ext':
                if (!$val['current']) {
                    $val['title'] = '不支持';
                    $val['icon']  = 'fa-remove text-danger';
                    session('error', true);
                }
                break;
            case 'func':
                if (!function_exists($val['name'])) {
                    $val['title'] = '不支持';
                    $val['icon']  = 'fa-remove text-danger';
                    session('error', true);
                }
                break;
        }
    }

    return $items;
}

/**
 * 创建数据表
 * @param  resource $db 数据库连接资源
 */
function create_tables($db, $prefix = '')
{
    //读取SQL文件
    if (is_file('./Data/install.sql')) {
        $sql = file_get_contents('./Data/install.sql');
    } else {
        $sql = file_get_contents(MODULE_PATH . 'Data/install.sql');
    }
    $sql = str_replace("\r", "\n", $sql);
    $sql = explode(";\n", $sql);

    //替换表前缀
    $orginal = C('ORIGINAL_TABLE_PREFIX');
    $sql     = str_replace(" `{$orginal}", " `{$prefix}", $sql);

    //开始安装
    show_msg('开始安装数据库...');
    foreach ($sql as $value) {
        $value = trim($value);
        if (empty($value)) {
            continue;
        }

        if (substr($value, 0, 12) == 'CREATE TABLE') {
            $name = preg_replace("/^CREATE TABLE `(\w+)` .*/s", "\\1", $value);
            $msg  = "创建数据表{$name}";
            if (false !== $db->execute($value)) {
                show_msg($msg . '...成功');
            } else {
                show_msg($msg . '...失败！', 'error');
                session('error', true);
            }
        } else {
            $db->execute($value);
        }
    }
}

/**
 * 写入配置文件
 * @param  array $config 配置信息
 */
function write_config($config, $auth)
{
    if (is_array($config)) {
        //读取配置内容
        $conf = file_get_contents(MODULE_PATH . 'Data/config.tpl');

        //替换配置项
        foreach ($config as $name => $value) {
            $conf = str_replace("[{$name}]", $value, $conf);
        }
        $conf = str_replace('[AUTH_KEY]', $auth, $conf);

        //写入应用配置文件
        if (file_put_contents('./Data/db.php', $conf)) {
            show_msg('配置文件写入成功');
        } else {
            show_msg('配置文件写入失败！', 'error');
            session('error', true);
        }
        return true;
    }
}

/**
 * 及时显示提示信息
 * @param  string $msg 提示信息
 */
function show_msg($msg, $class = '')
{
    echo "<script type=\"text/javascript\">showmsg(\"{$msg}\", \"{$class}\")</script>";
    flush();
    ob_flush();
}

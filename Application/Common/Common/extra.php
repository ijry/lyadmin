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

// 兼容2.0的方法

require_once APP_DIR . 'Common/util/lyf/Request.class.php';
function request()
{
    return \lyf\Request::instance();
}

function url($url = '', $vars = '', $suffix = true, $domain = false)
{
    return U($url, $vars, $suffix, $domain);
}

function config($name = null, $value = null, $default = null)
{
    return C($name, $value, $default);
}

function exception($msg, $code = 0)
{
    return E($msg, $code);
}

function model($name = '', $layer = '')
{
    return D($name, $layer);
}

function db($name = '')
{
    return M($name = '');
}

function action($url, $vars = array(), $layer = '')
{
    return R($url, $vars, $layer);
}

function cache($name, $value = '', $options = null)
{
    return S($name, $value, $options);
}

function widget($name, $data = array())
{
    return W($name, $data = array());
}

function input($name, $default = '', $filter = null, $datas = null)
{
    return I($name, $default, $filter, $datas);
}

function debug($start, $end = '', $dec = 4)
{
    return G($start, $end, $dec);
}

<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------

require_once APP_DIR . 'Common/Common/developer.php'; // 加载开发者二次开发公共函数库
require_once APP_DIR . 'Common/Common/extra.php'; // 加载兼容公共函数库

/**
 * 处理插件钩子
 * @param string $hook   钩子名称
 * @param mixed $params 传入参数
 * @return void
 * @author jry <598821125@qq.com>
 */
function hook($hook, $params = array())
{
    $result = \Think\Hook::listen($hook, $params);
}

/**
 * 获取插件类的类名
 * @param strng $name 插件名
 * @author jry <598821125@qq.com>
 */
function get_addon_class($name)
{
    $class = "Addons\\{$name}\\{$name}Addon";
    return $class;
}

/**
 * 插件显示内容里生成访问插件的url
 * @param string $url url
 * @param array $param 参数
 * @author jry <598821125@qq.com>
 */
function addons_url($url, $param = array())
{
    return D('Admin/Addon')->getAddonUrl($url, $param);
}

/**
 * 兼容Nginx
 * @return array
 * @author jry <598821125@qq.com>
 */
if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

/**
 * POST数据提前处理
 * @return array
 * @author jry <598821125@qq.com>
 */
function format_data($data = null)
{
    //解析数据类似复选框类型的数组型值
    if (!$data) {
        $data = $_POST;
    }
    $data_object = new \lyf\Date;
    foreach ($data as $key => $val) {
        if (!is_array($val)) {
            $val = trim($val);
            if ($data_object->checkDatetime($val)) {
                $data[$key] = strtotime($val);
            } else if ($data_object->checkDatetime($val, 'Y-m-d H:i')) {
                $data[$key] = strtotime($val);
            } else if ($data_object->checkDatetime($val, 'Y-m-d')) {
                $data[$key] = strtotime($val);
            } else {
                $data[$key] = $val;
            }
        } else {
            $data[$key] = implode(',', $val);
        }
    }
    return $data;
}

/**
 * 获取所有数据并转换成一维数组
 * $model string 查询模型
 * $map array 查询条件
 * $extra string 额外增加数据
 * $key string 结果数组key
 * $config array Tree参数
 * @author jry <598821125@qq.com>
 */
function select_list_as_tree($model, $map = null, $extra = null, $key = 'id', $param = null)
{
    //获取列表
    $con['status'] = array('eq', 1);
    if ($map) {
        $con = array_merge($con, $map);
    }
    $model_object = D($model);
    if (in_array('sort', $model_object->getDbFields())) {
        $list = $model_object->where($con)->order('sort asc, id asc')->select();
    } else {
        $list = $model_object->where($con)->order('id asc')->select();
    }

    //转换成树状列表(非严格模式)
    $tree             = new \lyf\Tree();
    $_param           = array();
    $_param['title']  = 'title';
    $_param['pk']     = 'id';
    $_param['pid']    = 'pid';
    $_param['root']   = 0;
    $_param['scrict'] = true;
    if ($param) {
        $_param = array_merge($_param, $param);
    }
    $list = $tree->array2tree($list, $_param['title'], $_param['pk'], $_param['pid'], $_param['root'], $_param['scrict']);

    if ($extra) {
        $result[0] = $extra;
    }

    //转换成一维数组
    foreach ($list as $val) {
        $result[$val[$key]] = $val['title_show'];
    }
    return $result;
}

/**
 * 解析文档内容
 * @param string $str 待解析内容
 * @return string
 * @author jry <598821125@qq.com>
 */
function parse_content($str, $lazy = true)
{
    if (C('IS_API')) {
        $data_src = false;
    } else {
        if ($lazy) {
            $data_src = true;
        } else {
            $data_src = false;
        }
    }
    if ($data_src) {
        // 将img标签的src改为data-src用户前台图片lazyload加载
        if (C('STATIC_DOMAIN')) {
            $tmp = preg_replace('/<img.*?src="(\/.*?)"(.*?)>/i', "<img class='lazy lazy-fadein img-responsive' style='display:inline-block;' data-src='" . C('STATIC_DOMAIN') . "$1'>", $str);
        } else {
            $tmp = preg_replace('/<img.*?src="(\/.*?)"(.*?)>/i', "<img class='lazy lazy-fadein img-responsive' style='display:inline-block;' data-src='" . C('TOP_HOME_DOMAIN') . "$1'>", $str);
        }
    } else {
        // 将img标签的src补充域名
        if (C('STATIC_DOMAIN')) {
            $tmp = preg_replace('/<img.*?src="(\/.*?)"(.*?)>/i', "<img class='lazy lazy-fadein img-responsive' style='display:inline-block;' src='" . C('STATIC_DOMAIN') . "$1'>", $str);
        } else {
            $tmp = preg_replace('/<img.*?src="(\/.*?)"(.*?)>/i', "<img class='lazy lazy-fadein img-responsive' style='display:inline-block;' src='" . C('TOP_HOME_DOMAIN') . "$1'>", $str);
        }
    }
    return $tmp;
}

/**
 * 字符串截取(中文按2个字符数计算)，支持中文和其他编码
 * @static
 * @access public
 * @param str $str 需要转换的字符串
 * @param str $start 开始位置
 * @param str $length 截取长度
 * @param str $charset 编码格式
 * @param str $suffix 截断显示字符
 * @return str
 */
function cut_str($str, $start, $length, $charset = 'utf-8', $suffix = true)
{
    return \lyf\Str::cutStr(
        $str, $start, $length, $charset, $suffix
    );
}

/**
 * 过滤标签，输出纯文本
 * @param string $str 文本内容
 * @return string 处理后内容
 * @author jry <598821125@qq.com>
 */
function html2text($str)
{
    return \lyf\Str::html2text($str);
}

/**
 * 友好的时间显示
 * @param int    $sTime 待显示的时间
 * @param string $type  类型. normal | mohu | full | ymd | other
 * @param string $alt   已失效
 * @return string
 * @author jry <598821125@qq.com>
 */
function friendly_date($sTime, $type = 'normal', $alt = 'false')
{
    $date = new \lyf\Date((int) $sTime);
    return $date->friendlyDate($type, $alt);
}

/**
 * 时间戳格式化
 * @param int $time
 * @return string 完整的时间显示
 * @author jry <598821125@qq.com>
 */
function time_format($time = null, $format = 'Y-m-d H:i')
{
    if (!$time) {
        return '';
    } else {
        return date($format, intval($time));
    }
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string
 * @author jry <598821125@qq.com>
 */
function user_md5($str, $auth_key = '')
{
    if (!$auth_key) {
        $auth_key = C('AUTH_KEY') ?: 'CoreThink';
    }
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author jry <598821125@qq.com>
 */
function is_login()
{
    return D('Admin/User')->is_login();
}

/**
 * 检测用户是否VIP
 * @return integer VIP等级
 * @author jry <598821125@qq.com>
 */
function is_vip($uid)
{
    if (D('Admin/Module')->where('name="Vip" and status="1"')->count()) {
        $uid = $uid ? $uid : is_login();
        return D('Vip/Index')->isVip($uid);
    }
    return false;
}

/**
 * 获取上传文件路径
 * @param  int $id 文件ID
 * @return string
 * @author jry <598821125@qq.com>
 */
function get_cover($id = null, $type = null)
{
    return D('Admin/Upload')->getCover($id, $type);
}

/**
 * 是否微信访问
 * @return bool
 * @author jry <598821125@qq.com>
 */
function is_weixin()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    } else {
        return false;
    }
}

/**
 * 当前请求的host(不包含端口)
 * @access public
 * @return string
 */
function hostname()
{
    $host = explode(':', $_SERVER['HTTP_HOST']);
    return $host[0];
}

/**
 * 自动生成URL，支持在后台生成前台链接
 * @param string $url URL表达式，格式：'[模块/控制器/操作#锚点@域名]?参数1=值1&参数2=值2...'
 * @param string|array $vars 传入的参数，支持数组和字符串
 * @param string|boolean $suffix 伪静态后缀，默认为true表示获取配置值
 * @param boolean $domain 是否显示域名
 * @return string
 * @author jry <598821125@qq.com>
 */
function url_home($url = '', $vars = '', $suffix = true, $domain = true)
{
    $url = U($url, $vars, $suffix, $domain);
    if (MODULE_MARK === 'Admin') {
        $url_model = D('Admin/Config')->where(array('name' => 'URL_MODEL'))->getField('value');
        switch ($url_model) {
            case '1':
                $result = strtr($url, array('admin.php?s=' => 'index.php'));
                break;
            case '2':
                $result = strtr($url, array('admin.php?s=/' => ''));
                break;
            case '3':
                $result = strtr($url, array('admin.php' => 'index.php'));
                break;
            default:
                $result = strtr($url, array('admin.php' => 'index.php'));
                break;
        }
        return $result;
    } else {
        return $url;
    }
}

// 兼容旧版本
function oc_url($url = '', $vars = '', $suffix = true, $domain = true)
{
    url_home($url, $vars, $suffix, $domain);
}

/**
 * 自动生成URL，支持在前台生成后台链接
 * @param string $url URL表达式，格式：'[模块/控制器/操作#锚点@域名]?参数1=值1&参数2=值2...'
 * @param string|array $vars 传入的参数，支持数组和字符串
 * @param string|boolean $suffix 伪静态后缀，默认为true表示获取配置值
 * @param boolean $domain 是否显示域名
 * @return string
 * @author jry <598821125@qq.com>
 */
function url_admin($url = '', $vars = '', $suffix = true, $domain = true)
{
    if (MODULE_MARK === 'Home') {
        $url_model = D('Admin/Config')->where(array('name' => 'URL_MODEL'))->getField('value');
        C('URL_MODEL', 3); // 临时改变URL模式
        $url = U($url, $vars, $suffix, $domain);
        C('URL_MODEL', $url_model); // 临时改变URL模式
        $result = strtr($url, array('index.php' => 'admin.php'));
        return $result;
    } else {
        return $url = U($url, $vars, $suffix, $domain);
    }
}

/**
 * 判断模块是否安装并且开启
 * @param string $module 模块名
 * @return boolean
 * @author jry <598821125@qq.com>
 */
function exist_module($module)
{
    if ($module) {
        $map           = array();
        $map['name']   = $module;
        $map['status'] = 1;
        $result        = D('Admin/Module')->where($map)->find();
        if (is_dir(APP_PATH . $result['name'])) {
            return $result;
        }
    }
    return false;
}

/**
 * 判断插件是否安装并且开启
 * @param string $module 插件名
 * @return boolean
 * @author jry <598821125@qq.com>
 */
function exist_addon($addon)
{
    if ($addon) {
        $map           = array();
        $map['name']   = $addon;
        $map['status'] = 1;
        $result        = D('Admin/Addon')->where($map)->find();
        if (is_dir(C('ADDON_PATH') . $result['name'])) {
            return $result;
        }
    }
    return false;
}

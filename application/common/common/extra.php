<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------

//------------------------
// 兼容TP3助手函数
//-------------------------

use think\Cache;
use think\Config;
use think\Db;
use think\Debug;
use think\Lang;
use think\Request;
use think\Url;

define('NOW_TIME', $_SERVER['REQUEST_TIME']);
define('IS_GET', request()->isGet());
define('IS_POST', request()->isPost());
define('IS_AJAX', request()->isAjax());

if (!function_exists('A')) {
    /**
     * 调用模块的操作方法 参数格式 [模块/控制器/]操作
     * @param string        $url 调用地址
     * @param string|array  $vars 调用参数 支持字符串和数组
     * @param string        $layer 要调用的控制层名称
     * @param bool          $appendSuffix 是否添加类名后缀
     * @return mixed
     */
    function A($url, $vars = [], $layer = 'controller', $appendSuffix = false)
    {
        return action($url, $vars, $layer, $appendSuffix);
    }
}

if (!function_exists('C')) {
    /**
     * 获取和设置配置参数
     * @param string|array  $name 参数名
     * @param mixed         $value 参数值
     * @param string        $range 作用域
     * @return mixed
     */
    function C($name = '', $value = null, $range = '')
    {
        return config($name, $value, $range);
    }
}

if (!function_exists('D')) {
    /**
     * 实例化Model
     * @param string    $name Model名称
     * @param string    $layer 业务层名称
     * @param bool      $appendSuffix 是否添加类名后缀
     * @return \think\Model
     */
    function D($name = '', $layer = 'model', $appendSuffix = false)
    {
        return model($name, $layer, $appendSuffix);
    }
}

if (!function_exists('M')) {
    /**
     * 实例化一个没有模型文件的Model
     * @param string $name Model名称 支持指定基础模型 例如 MongoModel:User
     * @param string $tablePrefix 表前缀
     * @param mixed $connection 数据库连接信息
     * @return Think\Model
     */
    function M($name = '', $tablePrefix = '', $connection = '')
    {
        static $_model = array();
        if (strpos($name, ':')) {
            list($class, $name) = explode(':', $name);
        } else {
            $class = '\\app\\common\\model\\Model';
        }
        $guid = (is_array($connection) ? implode('', $connection) : $connection) . $tablePrefix . $name . '_' . $class;
        if (!isset($_model[$guid])) {
            $_model[$guid] = new $class($name, $tablePrefix, $connection);
        }

        return $_model[$guid];
    }
}

if (!function_exists('U')) {
    /**
     * Url生成
     * @param string        $url 路由地址
     * @param string|array  $value 变量
     * @param bool|string   $suffix 前缀
     * @param bool|string   $domain 域名
     * @return string
     */
    function U($url = '', $vars = '', $suffix = true, $domain = false)
    {
        return url($url, $vars, $suffix, $domain);
    }
}

if (!function_exists('S')) {
    /**
     * 缓存管理
     * @param mixed     $name 缓存名称，如果为数组表示进行缓存设置
     * @param mixed     $value 缓存值
     * @param mixed     $options 缓存参数
     * @param string    $tag 缓存标签
     * @return mixed
     */
    function S($name, $value = '', $options = null, $tag = null)
    {
        return cache($name, $value, $options, $tag);
    }
}

if (!function_exists('I')) {
    /**
     * 获取输入数据 支持默认值和过滤
     * @param string    $key 获取的变量名
     * @param mixed     $default 默认值
     * @param string    $filter 过滤方法
     * @return mixed
     */
    function I($key = '', $default = null, $filter = null)
    {
        return input($key, $default, $filter);
    }
}

if (!function_exists('E')) {
    /**
     * 抛出异常处理
     *
     * @param string    $msg  异常消息
     * @param integer   $code 异常代码 默认为0
     * @param string    $exception 异常类
     *
     * @throws Exception
     */
    function E($msg, $code = 0, $exception = '')
    {
        return exception($msg, $code, $exception);
    }
}

if (!function_exists('L')) {
    /**
     * 获取语言变量值
     * @param string    $name 语言变量名
     * @param array     $vars 动态变量值
     * @param string    $lang 语言
     * @return mixed
     */
    function L($name, $vars = [], $lang = '')
    {
        return lang($name, $vars, $lang);
    }
}

if (!function_exists('G')) {
    /**
     * 记录时间（微秒）和内存使用情况
     * @param string            $start 开始标签
     * @param string            $end 结束标签
     * @param integer|string    $dec 小数位 如果是m 表示统计内存占用
     * @return mixed
     */
    function G($start, $end = '', $dec = 6)
    {
        return debug($start, $end, $dec);
    }
}

/**
 * 字符串命名风格转换
 * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
 * @param string $name 字符串
 * @param integer $type 转换类型
 * @return string
 */
function parse_name($name, $type = 0)
{
    if ($type) {
        return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function ($match) {return strtoupper($match[1]);}, $name));
    } else {
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
}

/**
 * 设置和获取统计数据
 * 使用方法:
 * <code>
 * N('db',1); // 记录数据库操作次数
 * N('read',1); // 记录读取次数
 * echo N('db'); // 获取当前页面数据库的所有操作次数
 * echo N('read'); // 获取当前页面读取次数
 * </code>
 * @param string $key 标识位置
 * @param integer $step 步进值
 * @param boolean $save 是否保存结果
 * @return mixed
 */
function N($key, $step = 0, $save = false)
{
    static $_num = array();
    if (!isset($_num[$key])) {
        $_num[$key] = (false !== $save) ? S('N_' . $key) : 0;
    }
    if (empty($step)) {
        return $_num[$key];
    } else {
        $_num[$key] = $_num[$key] + (int) $step;
    }
    if (false !== $save) {
        // 保存结果
        S('N_' . $key, $_num[$key], $save);
    }
    return null;
}

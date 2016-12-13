<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Common\Behavior;

use Think\Behavior;

defined('THINK_PATH') or exit();
/**
 * 根据不同情况读取数据库的配置信息并与本地配置合并
 * 本行为扩展很重要会影响核心系统前后台、模块功能及模版主题使用
 * 如非必要或者并不是十分了解系统架构不推荐更改
 * @author jry <598821125@qq.com>
 */
class InitConfigBehavior extends Behavior
{
    /**
     * 行为扩展的执行入口必须是run
     * @author jry <598821125@qq.com>
     */
    public function run(&$content)
    {
        // 安装模式下直接返回
        if (defined('BIND_MODULE') && BIND_MODULE === 'Install') {
            return;
        }

        // 读取数据库中的配置
        $system_config = S('DB_CONFIG_DATA');
        if (!$system_config || APP_DEBUG === true) {
            // 获取所有系统配置
            $system_config = D('Admin/Config')->lists();

            // SESSION与COOKIE与前缀设置避免冲突
            $system_config['SESSION_PREFIX'] = strtolower(ENV_PRE . MODULE_MARK . '_'); // Session前缀
            $system_config['COOKIE_PREFIX']  = strtolower(ENV_PRE . MODULE_MARK . '_'); // Cookie前缀

            // Session数据表
            $system_config['SESSION_TABLE'] = C('DB_PREFIX') . 'admin_session';

            // 获取所有安装的模块配置
            $module_list = D('Admin/Module')->where(array('status' => '1'))->select();
            foreach ($module_list as $val) {
                $module_config[strtolower($val['name'] . '_config')]                = json_decode($val['config'], true);
                $module_config[strtolower($val['name'] . '_config')]['module_name'] = $val['name'];
            }
            if ($module_config) {
                // 合并模块配置
                $system_config = array_merge($system_config, $module_config);

                // 加载模块标签库及行为扩展
                $system_config['TAGLIB_PRE_LOAD'] = explode(',', C('TAGLIB_PRE_LOAD')); // 先取出配置文件中定义的否则会被覆盖
                foreach ($module_config as $key => $val) {
                    // 加载模块标签库
                    if (isset($val['taglib'])) {
                        foreach ($val['taglib'] as $tag) {
                            $tag_path = APP_PATH . $val['module_name'] . '/TagLib/' . $tag . '.class.php';
                            if (is_file($tag_path)) {
                                $system_config['TAGLIB_PRE_LOAD'][] = $val['module_name'] . '\\TagLib\\' . $tag;
                            }
                        }
                    }

                    // 加载模块行为扩展
                    if (isset($val['behavior'])) {
                        foreach ($val['behavior'] as $bhv) {
                            $bhv_path = APP_PATH . $val['module_name'] . '/Behavior/' . $bhv . 'Behavior.class.php';
                            if (is_file($bhv_path)) {
                                \Think\Hook::add('lingyun_behavior', $val['module_name'] . '\\Behavior\\' . $bhv . 'Behavior');
                            }
                        }
                    }
                }
                $system_config['TAGLIB_PRE_LOAD'] = implode(',', $system_config['TAGLIB_PRE_LOAD']);
            }

            // 获取所有安装的插件配置
            $addon_list = D('Admin/Addon')->where(array('status' => '1'))->select();
            foreach ($addon_list as $val) {
                $addon_config[strtolower($val['name'] . '_addon_config')]               = json_decode($val['config'], true);
                $addon_config[strtolower($val['name'] . '_addon_config')]['addon_name'] = $val['name'];
            }
            if ($addon_config) {
                // 合并模块配置
                $system_config = array_merge($system_config, $addon_config);

                // 加载插件标签库及行为扩展
                $system_config['TAGLIB_PRE_LOAD'] = explode(',', $system_config['TAGLIB_PRE_LOAD']); // 先取出配置文件中定义的否则会被覆盖
                foreach ($addon_config as $key => $val) {
                    // 加载模块标签库
                    if (isset($val['taglib'])) {
                        foreach ($val['taglib'] as $tag) {
                            $tag_path = C('ADDON_PATH') . $val['addon_name'] . '/TagLib/' . $tag . '.class.php';
                            if (is_file($tag_path)) {
                                $system_config['TAGLIB_PRE_LOAD'][] = 'Addons\\' . $val['addon_name'] . '\\TagLib\\' . $tag;
                            }
                        }
                    }

                    // 加载插件行为扩展
                    if (isset($val['behavior'])) {
                        foreach ($val['behavior'] as $bhv) {
                            $bhv_path = C('ADDON_PATH') . $val['addon_name'] . '/Behavior/' . $bhv . '.class.php';
                            if (is_file($bhv_path)) {
                                \Think\Hook::add('lingyun_behavior', 'Addons\\' . $val['addon_name'] . '\\Behavior\\' . $bhv);
                            }
                        }
                    }
                }
                $system_config['TAGLIB_PRE_LOAD'] = implode(',', $system_config['TAGLIB_PRE_LOAD']);
            }

            S('DB_CONFIG_DATA', $system_config, 3600); // 缓存配置
        }

        // 系统主页地址配置
        $system_config['TOP_HOME_DOMAIN'] = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
        $system_config['HOME_DOMAIN']     = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
        $system_config['HOME_PAGE']       = $system_config['HOME_DOMAIN'] . __ROOT__;
        $system_config['TOP_HOME_PAGE']   = $system_config['TOP_HOME_DOMAIN'] . __ROOT__;

        // 如果是后台并且不是Admin模块则设置默认控制器层为Admin
        if (MODULE_MARK === 'Admin' && MODULE_NAME !== 'Admin') {
            $system_config['DEFAULT_C_LAYER'] = 'Admin';
            $system_config['VIEW_PATH']       = APP_PATH . MODULE_NAME . '/View/Admin/';
        }

        // 静态资源域名
        $current_domain                  = $system_config['TOP_HOME_PAGE'];
        $system_config['CURRENT_DOMAIN'] = $current_domain;

        // 模版参数配置
        $system_config['TMPL_PARSE_STRING']             = C('TMPL_PARSE_STRING'); // 先取出配置文件中定义的否则会被覆盖
        $system_config['TMPL_PARSE_STRING']['__IMG__']  = $current_domain . '/' . APP_PATH . MODULE_NAME . '/View/Public/img';
        $system_config['TMPL_PARSE_STRING']['__CSS__']  = $current_domain . '/' . APP_PATH . MODULE_NAME . '/View/Public/css';
        $system_config['TMPL_PARSE_STRING']['__JS__']   = $current_domain . '/' . APP_PATH . MODULE_NAME . '/View/Public/js';
        $system_config['TMPL_PARSE_STRING']['__LIBS__'] = $current_domain . '/' . APP_PATH . MODULE_NAME . '/View/Public/libs';

        // 前台默认模块静态资源路径及模板继承基本模板
        $default_public_path = APP_PATH . C('DEFAULT_MODULE') . '/View/Public';
        if (is_dir($default_public_path)) {
            $system_config['DEFAULT_PUBLIC_LAYOUT']                 = $default_public_path . '/layout.html';
            $system_config['TMPL_PARSE_STRING']['__DEFAULT_IMG__']  = $system_config['TOP_HOME_PAGE'] . ltrim($default_public_path, '.') . '/img';
            $system_config['TMPL_PARSE_STRING']['__DEFAULT_CSS__']  = $system_config['TOP_HOME_PAGE'] . ltrim($default_public_path, '.') . '/css';
            $system_config['TMPL_PARSE_STRING']['__DEFAULT_JS__']   = $system_config['TOP_HOME_PAGE'] . ltrim($default_public_path, '.') . '/js';
            $system_config['TMPL_PARSE_STRING']['__DEFAULT_LIBS__'] = $system_config['TOP_HOME_PAGE'] . ltrim($default_public_path, '.') . '/libs';
        }

        C($system_config); // 添加配置
    }
}

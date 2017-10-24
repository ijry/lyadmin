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
use Think\Hook;

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

            // 修复默认主题被数据库覆盖
            $system_config['DEFAULT_MODULE'] = C('DEFAULT_MODULE');

            // SESSION与COOKIE与前缀设置避免冲突
            $system_config['SESSION_PREFIX'] = strtolower(ENV_PRE . MODULE_MARK . '_'); // Session前缀
            $system_config['COOKIE_PREFIX']  = strtolower(ENV_PRE . MODULE_MARK . '_'); // Cookie前缀

            // Session数据表
            $system_config['SESSION_TABLE'] = C('DB_PREFIX') . 'admin_session';

            // 加载模块标签库及行为扩展
            $system_config['TAGLIB_PRE_LOAD'] = explode(',', C('TAGLIB_PRE_LOAD')); // 先取出配置文件中定义的否则会被覆盖

            // 获取所有安装的模块配置
            $module_list = D('Admin/Module')->where(array('status' => '1'))->select();
            foreach ($module_list as $_module) {
                // 将模块自定义的配置合并到全局配置
                $module_config[strtolower($_module['name'] . '_config')]['module_name'] = $_module['name'];
                $module_config[strtolower($_module['name'] . '_config')]                = json_decode($_module['config'], true);

                // 加载模块标签库
                $tag_path = APP_DIR . $_module['name'] . '/TagLib/' . $_module['name'] . '.class.php';
                if (is_file($tag_path)) {
                    $system_config['TAGLIB_PRE_LOAD'][] = $_module['name'] . '\\TagLib\\' . $_module['name'];
                }

                // 加载模块行为扩展
                $bhv_path = APP_DIR . $_module['name'] . '/Behavior/' . $_module['name'] . 'Behavior.class.php';
                if (is_file($bhv_path)) {
                    Hook::add('lingyun_behavior', $_module['name'] . '\\Behavior\\' . $_module['name'] . 'Behavior');
                }
            }
            if ($module_config) {
                // 合并模块配置
                $system_config = array_merge($system_config, $module_config);
            }

            // 获取所有安装的插件配置
            $addon_list = D('Admin/Addon')->where(array('status' => '1'))->select();
            foreach ($addon_list as $_addon) {
                // 将插件自定义的配置合并到全局配置
                $addon_config[strtolower($_addon['name'] . '_addon_config')]['addon_name'] = $_addon['name'];
                $addon_config[strtolower($_addon['name'] . '_addon_config')]               = json_decode($_addon['config'], true);

                // 加载插件标签库
                $tag_path = C('ADDON_PATH') . $_addon['name'] . '/TagLib/' . $_addon['name'] . 'Addon.class.php';
                if (is_file($tag_path)) {
                    $system_config['TAGLIB_PRE_LOAD'][] = 'Addons\\' . $_addon['name'] . '\\TagLib\\' . $_addon['name'] . 'Addon';
                }

                // 加载插件行为扩展
                $bhv_path = C('ADDON_PATH') . $_addon['name'] . '/Behavior/' . $_addon['name'] . 'Addon.class.php';
                if (is_file($bhv_path)) {
                    Hook::add('lingyun_behavior', 'Addons\\' . $_addon['name'] . '\\Behavior\\' . $_addon['name'] . 'Addon');
                }
            }
            if ($addon_config) {
                // 合并模块配置
                $system_config = array_merge($system_config, $addon_config);
            }

            // 存储行为扩展
            $system_config['lingyun_hooks'] = Hook::get();

            // 格式化加载标签库
            $system_config['TAGLIB_PRE_LOAD'] = implode(',', $system_config['TAGLIB_PRE_LOAD']);

            // 加载Formbuilder扩展类型
            $system_config['FORM_ITEM_TYPE'] = C('FORM_ITEM_TYPE');
            $formbuilder_extend              = explode(',', D('Admin/Hook')->getFieldByName('FormBuilderExtend', 'addons'));
            if ($formbuilder_extend) {
                $addon_object = D('Admin/Addon');
                foreach ($formbuilder_extend as $val) {
                    $temp = json_decode($addon_object->getFieldByName($val, 'config'), true);
                    if ($temp['status']) {
                        $form_type[$temp['form_item_type_name']] = array($temp['form_item_type_title'], $temp['form_item_type_field']);
                        $system_config['FORM_ITEM_TYPE']         = array_merge($system_config['FORM_ITEM_TYPE'], $form_type);
                    }
                }
            }

            // 授权数据
            $system_config['SN_DECODE'] = \lyf\Crypt::decrypt($system_config['AUTH_SN'], sha1(md5($system_config['AUTH_USERNAME'])));

            S('DB_CONFIG_DATA', $system_config, 3600); // 缓存配置
        }

        // 导入Hook行为扩展
        if ($system_config['lingyun_hooks']) {
            Hook::import($system_config['lingyun_hooks'], false);
        }

        // 移动端强制后台传统视图
        if (request()->isMobile()) {
            $system_config['IS_MOBILE']  = true;
            $system_config['ADMIN_TABS'] = 0;
        } else {
            $system_config['IS_MOBILE'] = false;
        }

        // 强制WAP模式
        if ($system_config['WAP_MODE']) {
            $system_config['IS_MOBILE'] = true;
        }

        // 如果是后台并且不是Admin模块则设置默认控制器层为Admin
        if (MODULE_MARK === 'Admin' && request()->module() !== 'Admin') {
            $system_config['DEFAULT_C_LAYER'] = 'Admin';
            $system_config['VIEW_PATH']       = APP_DIR . request()->module() . '/View/Admin/';
        }

        // 模版参数配置
        $system_config['TMPL_PARSE_STRING']                   = C('TMPL_PARSE_STRING'); // 先取出配置文件中定义的否则会被覆盖
        $system_config['TMPL_PARSE_STRING']['__PUBLIC__']     = C('TOP_HOME_DOMAIN') . $system_config['TMPL_PARSE_STRING']['__PUBLIC__'];
        $system_config['TMPL_PARSE_STRING']['__LYUI__']       = C('TOP_HOME_DOMAIN') . $system_config['TMPL_PARSE_STRING']['__LYUI__'];
        $system_config['TMPL_PARSE_STRING']['__ADMIN_IMG__']  = C('TOP_HOME_DOMAIN') . $system_config['TMPL_PARSE_STRING']['__ADMIN_IMG__'];
        $system_config['TMPL_PARSE_STRING']['__ADMIN_CSS__']  = C('TOP_HOME_DOMAIN') . $system_config['TMPL_PARSE_STRING']['__ADMIN_CSS__'];
        $system_config['TMPL_PARSE_STRING']['__ADMIN_JS__']   = C('TOP_HOME_DOMAIN') . $system_config['TMPL_PARSE_STRING']['__ADMIN_JS__'];
        $system_config['TMPL_PARSE_STRING']['__ADMIN_LIBS__'] = C('TOP_HOME_DOMAIN') . $system_config['TMPL_PARSE_STRING']['__ADMIN_LIBS__'];
        $system_config['TMPL_PARSE_STRING']['__HOME_IMG__']   = C('TOP_HOME_DOMAIN') . $system_config['TMPL_PARSE_STRING']['__HOME_IMG__'];
        $system_config['TMPL_PARSE_STRING']['__HOME_CSS__']   = C('TOP_HOME_DOMAIN') . $system_config['TMPL_PARSE_STRING']['__HOME_CSS__'];
        $system_config['TMPL_PARSE_STRING']['__HOME_JS__']    = C('TOP_HOME_DOMAIN') . $system_config['TMPL_PARSE_STRING']['__HOME_JS__'];
        $system_config['TMPL_PARSE_STRING']['__HOME_LIBS__']  = C('TOP_HOME_DOMAIN') . $system_config['TMPL_PARSE_STRING']['__HOME_LIBS__'];
        $system_config['TMPL_PARSE_STRING']['__IMG__']        = C('TOP_HOME_PAGE') . ltrim(APP_DIR, '.') . request()->module() . '/View/Public/img';
        $system_config['TMPL_PARSE_STRING']['__CSS__']        = C('TOP_HOME_PAGE') . ltrim(APP_DIR, '.') . request()->module() . '/View/Public/css';
        $system_config['TMPL_PARSE_STRING']['__JS__']         = C('TOP_HOME_PAGE') . ltrim(APP_DIR, '.') . request()->module() . '/View/Public/js';
        $system_config['TMPL_PARSE_STRING']['__LIBS__']       = C('TOP_HOME_PAGE') . ltrim(APP_DIR, '.') . request()->module() . '/View/Public/libs';

        // 前台默认模块静态资源路径及模板继承基本模板
        $default_public_path = APP_DIR . C('DEFAULT_MODULE') . '/View/Public';
        if (is_dir($default_public_path)) {
            $system_config['DEFAULT_PUBLIC_LAYOUT']                 = $default_public_path . '/layout.html';
            $system_config['TMPL_PARSE_STRING']['__DEFAULT_IMG__']  = C('TOP_HOME_PAGE') . ltrim($default_public_path, '.') . '/img';
            $system_config['TMPL_PARSE_STRING']['__DEFAULT_CSS__']  = C('TOP_HOME_PAGE') . ltrim($default_public_path, '.') . '/css';
            $system_config['TMPL_PARSE_STRING']['__DEFAULT_JS__']   = C('TOP_HOME_PAGE') . ltrim($default_public_path, '.') . '/js';
            $system_config['TMPL_PARSE_STRING']['__DEFAULT_LIBS__'] = C('TOP_HOME_PAGE') . ltrim($default_public_path, '.') . '/libs';
        }

        C($system_config); // 添加配置
    }
}

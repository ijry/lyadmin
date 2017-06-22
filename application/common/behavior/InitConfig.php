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
namespace app\common\behavior;

defined('THINK_PATH') or exit();

/**
 * 根据不同情况读取数据库的配置信息并与本地配置合并
 * 本行为扩展很重要会影响核心系统前后台、模块功能及模版主题使用
 * 如非必要或者并不是十分了解系统架构不推荐更改
 * @author jry <598821125@qq.com>
 */
class InitConfig
{
    /**
     * 行为扩展的执行入口必须是run
     * @author jry <598821125@qq.com>
     */
    public function run(&$content)
    {
        // 安装模式下直接返回
        if (defined('BIND_MODULE') && BIND_MODULE === 'install') {
            return;
        }

        // 读取数据库中的配置
        $system_config = cache('db_config_data');
        if (!$system_config || APP_DEBUG === true) {
            // 获取所有系统配置
            $system_config = D('Admin/Config')->lists();
            //$system_config = array_merge(C(''), $system_config);

            // SESSION与COOKIE与前缀设置避免冲突
            $system_config['session_prefix'] = strtolower(ENV_PRE . MODULE_MARK . '_'); // Session前缀
            $system_config['cookie_prefix']  = strtolower(ENV_PRE . MODULE_MARK . '_'); // Cookie前缀

            // Session数据表
            $system_config['session_table'] = C('DB_PREFIX') . 'admin_session';

            // 加载模块标签库及行为扩展
            $system_config['template']        = C('template'); // 先取出配置文件中定义的否则会被覆盖
            $system_config['taglib_pre_load'] = explode(',', $system_config['template']['taglib_pre_load']); // 先取出配置文件中定义的否则会被覆盖

            // 获取所有安装的模块配置
            $module_list = D('Admin/Module')->where(array('status' => '1'))->select();
            foreach ($module_list as $_module) {
                // 将模块自定义的配置合并到全局配置
                $module_config[strtolower($_module['name'] . '_config')]['module_name'] = $_module['name'];
                $module_config[strtolower($_module['name'] . '_config')]                = json_decode($_module['config'], true);

                // 加载模块标签库
                $tag_path = APP_DIR . $_module['name'] . '/taglib/' . $_module['name'] . '.php';
                if (is_file($tag_path)) {
                    $system_config['taglib_pre_load'][] = $_module['name'] . '\\taglib\\' . $_module['name'];
                }

                // 加载模块行为扩展
                $bhv_path = APP_DIR . $_module['name'] . '/behavior/' . $_module['name'] . 'Behavior.php';
                if (is_file($bhv_path)) {
                    \think\Hook::add('lingyun_behavior', $_module['name'] . '\\behavior\\' . $_module['name'] . 'Behavior');
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
                $tag_path = config('addon_path') . lcfirst($_addon['name']) . '/taglib/' . $_addon['name'] . 'Addon.php';
                if (is_file($tag_path)) {
                    $system_config['taglib_pre_load'][] = '\\addon\\' . lcfirst($_addon['name']) . '\\taglib\\' . $_addon['name'] . 'Addon';
                }

                // 加载插件行为扩展
                $bhv_path = config('addon_path') . lcfirst($_addon['name']) . '/behavior/' . $_addon['name'] . 'Addon.php';
                if (is_file($bhv_path)) {
                    \think\Hook::add('lingyun_behavior', '\\addon\\' . lcfirst($_addon['name']) . '\\behavior\\' . $_addon['name'] . 'Addon');
                }
            }
            if ($addon_config) {
                // 合并模块配置
                $system_config = array_merge($system_config, $addon_config);
            }

            // 格式化加载标签库
            $system_config['template']['taglib_pre_load'] = implode(',', $system_config['taglib_pre_load']);

            // 加载Formbuilder扩展类型
            $system_config['form_item_type'] = C('form_item_type');
            $formbuilder_extend              = explode(',', D('Admin/Hook')->getFieldByName('FormBuilderExtend', 'addons'));
            if ($formbuilder_extend) {
                $addon_object = D('Admin/Addon');
                foreach ($formbuilder_extend as $val) {
                    $temp = json_decode($addon_object->getFieldByName($val, 'config'), true);
                    if ($temp['status']) {
                        $form_type[$temp['form_item_type_name']] = array($temp['form_item_type_title'], $temp['form_item_type_field']);
                        $system_config['form_item_type']         = array_merge($system_config['form_item_type'], $form_type);
                    }
                }
            }

            cache('db_config_data', $system_config, 3600); // 缓存配置
        }

        // 移动端强制后台传统视图
        if (request()->isMobile()) {
            $system_config['is_mobile']  = true;
            $system_config['admin_tabs'] = 0;
        } else {
            $system_config['is_mobile'] = false;
        }

        // 强制WAP模式
        if ($system_config['WAP_MODE']) {
            $system_config['is_mobile'] = true;
        }

        // 如果是后台并且不是Admin模块则设置默认控制器层为Admin
        if (MODULE_MARK === 'Admin' && request()->module() !== '' && request()->module() !== 'admin') {
            $system_config['url_controller_layer']  = 'admin';
            $system_config['template']['view_path'] = APP_DIR . request()->module() . '/view/admin/';
        }

        // 模版参数配置
        $system_config['view_replace_str']             = config('view_replace_str'); // 先取出配置文件中定义的否则会被覆盖
        $system_config['view_replace_str']['__IMG__']  = config('top_home_page') . ltrim(APP_DIR, '.') . request()->module() . '/view/public/img';
        $system_config['view_replace_str']['__CSS__']  = config('top_home_page') . ltrim(APP_DIR, '.') . request()->module() . '/view/public/css';
        $system_config['view_replace_str']['__JS__']   = config('top_home_page') . ltrim(APP_DIR, '.') . request()->module() . '/view/public/js';
        $system_config['view_replace_str']['__LIBS__'] = config('top_home_page') . ltrim(APP_DIR, '.') . request()->module() . '/view/public/libs';

        // 前台默认模块静态资源路径及模板继承基本模板
        $default_public_path = APP_DIR . C('default_module') . '/view/public';
        if (is_dir($default_public_path)) {
            $system_config['default_public_layout']                = $default_public_path . '/layout.html';
            $system_config['view_replace_str']['__DEFAULT_IMG__']  = config('top_home_page') . ltrim($default_public_path, '.') . '/img';
            $system_config['view_replace_str']['__DEFAULT_CSS__']  = config('top_home_page') . ltrim($default_public_path, '.') . '/css';
            $system_config['view_replace_str']['__DEFAULT_JS__']   = config('top_home_page') . ltrim($default_public_path, '.') . '/js';
            $system_config['view_replace_str']['__DEFAULT_LIBS__'] = config('top_home_page') . ltrim($default_public_path, '.') . '/libs';
        }

        // 默认模块
        $system_config['default_module'] = $system_config['default_module'] ?: C('default_module');
        C($system_config); // 添加配置
    }
}

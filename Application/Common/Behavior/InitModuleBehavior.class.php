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
 * 初始化允许访问模块信息
 * @author jry <598821125@qq.com>
 */
class InitModuleBehavior extends Behavior
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

        // 数据缓存前缀
        $config['DATA_CACHE_PREFIX'] = strtolower(ENV_PRE . MODULE_MARK . '_');

        // 获取数据库存储的配置
        $database_config = D('Admin/Config')->lists();

        // 兼容2.0规范
        $config['APP_DEBUG']       = APP_DEBUG;
        $config['SHOW_PAGE_TRACE'] = $database_config['APP_TRACE'];

        // URL_MODEL必须在app_init阶段就从数据库读取出来应用
        // 不然系统就会读取config.php中的配置导致后台的配置失效
        $config['URL_MODEL'] = $database_config['URL_MODEL'];

        // 允许访问模块列表加上安装的功能模块
        $module_name_list = D('Admin/Module')
            ->where(array('status' => 1, 'is_system' => 0))
            ->getField('name', true);
        $module_allow_list = array_merge(
            C('MODULE_ALLOW_LIST'),
            $module_name_list
        );
        if (MODULE_MARK === 'Admin') {
            $module_allow_list[] = 'Admin';
            $config['URL_MODEL'] = 3;

            // 后台只输入{域名}/admin.php即可进入后台首页
            if (request()->pathinfo() === '/') {
                $_SERVER['PATH_INFO'] = 'Admin/Index/index';
            }
        }
        C('MODULE_ALLOW_LIST', $module_allow_list);

        // 设置默认模块
        if ($database_config['DEFAULT_MODULE'] && MODULE_MARK === 'Home') {
            $config['DEFAULT_MODULE'] = $database_config['DEFAULT_MODULE'];
        }

        // 系统主页地址配置
        $config['TOP_HOME_DOMAIN'] = request()->domain();
        $config['HOME_DOMAIN']     = request()->domain();
        $config['HOME_PAGE']       = $config['HOME_DOMAIN'] . __ROOT__;
        $config['TOP_HOME_PAGE']   = $config['TOP_HOME_DOMAIN'] . __ROOT__;

        C($config);
    }
}

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
        }
        C('MODULE_ALLOW_LIST', $module_allow_list);

        // 如果是后台访问自动设置默认模块为Admin
        if (MODULE_MARK === 'Admin') {
            C('DEFAULT_MODULE', 'Admin');
        }

        // 设置默认模块
        if ($database_config['DEFAULT_MODULE']) {
            $config['DEFAULT_MODULE'] = $database_config['DEFAULT_MODULE'];
        }

        // 设置WAP和微信标记
        define('IS_WAP', is_wap() ? true : false);
        define('IS_WEIXIN', is_weixin() ? true : false);

        // 获取不带端口的域名
        $_host = explode(':', $_SERVER['HTTP_HOST']);
        define('HTTP_HOST', $_host[0]);

        // 获取scheme
        define('HTTP_SCHEME', (is_ssl() ? 'https' : 'http'));

        // 获取域名
        define('HTTP_DOMAIN', HTTP_SCHEME . '://' . $_SERVER['HTTP_HOST']);

        C($config);
    }
}

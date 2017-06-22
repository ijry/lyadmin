<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\common\behavior;

defined('THINK_PATH') or exit();

/**
 * 初始化允许访问模块信息
 * @author jry <598821125@qq.com>
 */
class InitModule
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

        // 通过hook方法注入动态方法
        \think\Request::hook('isWeixin', 'is_weixin');
        \think\Request::hook('hostname', 'hostname');

        // 获取配置
        $config = config();

        // 数据缓存前缀
        $config['data_cache_prefix'] = strtolower(ENV_PRE . MODULE_MARK . '_');

        // 获取数据库存储的配置
        $database_config = model('Admin/Config')->lists();

        // 允许访问模块列表加上安装的功能模块
        $module_name_list = model('Admin/Module')
            ->where(array('status' => 1, 'is_system' => 0))
            ->getField('name', true);
        $module_allow_list = array_merge(
            config('module_allow_list'),
            $module_name_list
        );
        if (MODULE_MARK === 'Admin') {
            $module_allow_list[] = 'admin';

            // 后台只输入{域名}/admin.php即可进入后台首页
            if ($_SERVER['PATH_INFO'] === null || $_SERVER['PATH_INFO'] === '/') {
                $_SERVER['PATH_INFO'] = 'admin/index/index';
            }
        }
        config('module_allow_list', $module_allow_list);

        // 系统主页地址配置
        $config['top_home_domain'] = request()->domain();
        if (isset($config['app_sub_domain_deploy']) && $config['app_sub_domain_deploy']) {
            $host = explode('.', request()->hostname());
            if (count($host) > 2) {
                $config['top_home_domain'] = request()->scheme() . '://www' . strstr(request()->hostname(), '.');

                // 设置cookie和session的作用域
                $config['cookie_domain']             = strstr(request()->hostname(), '.');
                $config['session_options']           = C('session_options');
                $config['session_options']['domain'] = $config['COOKIE_DOMAIN'];
            }
        }
        $config['home_domain']   = request()->domain();
        $config['home_page']     = $config['home_domain'] . __ROOT__;
        $config['top_home_page'] = $config['top_home_domain'] . __ROOT__;

        // 模块初始化
        $pathinfo = explode('/', request()->pathinfo());
        request()->module(strtolower($pathinfo[0]));

        config($config);
    }
}

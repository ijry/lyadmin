<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\common\behavior;

use think\Hook;

defined('THINK_PATH') or exit();

/**
 * 初始化钩子信息
 * @author jry <598821125@qq.com>
 */
class InitHook
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

        // 添加插件配置
        $addon_config['addon_path']                        = './addon/';
        $addon_config['view_replace_str']                  = config('view_replace_str');
        $addon_config['view_replace_str']['__ADDON_DIR__'] = C('TOP_HOME_PAGE') . '/addon';
        config($addon_config);
        \think\Loader::addNamespace('addon', $addon_config['addon_path']);

        $data = cache('hooks');
        if (!$data || config('app_debug') === true) {
            $hooks = model('Admin/Hook')->getField('name,addons');
            foreach ($hooks as $hook => $value) {
                if ($value) {
                    $map['status'] = 1;
                    $names         = explode(',', $value);
                    $map['name']   = array('IN', $names);
                    $data          = model('Admin/Addon')->where($map)->getField('id,name');
                    if ($data) {
                        // 过滤掉插件目录不存在的插件
                        foreach ($data as $key => $val) {
                            $val = lcfirst($val);
                            if (!is_dir('./addon/' . $val)) {
                                unset($data[$key]);
                            }
                        }
                        $addons = array_intersect($names, $data);
                        Hook::add($hook, array_map('get_addon_class', $addons));
                    }
                }
            }
            cache('hooks', Hook::get());
        } else {
            Hook::import($data, false);
        }
    }
}

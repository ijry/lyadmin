<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Addons\RocketToTop;

use Common\Controller\Addon;

/**
 * 小火箭返回顶部
 * @jry <598821125@qq.com>
 */
class RocketToTopAddon extends Addon
{
    /**
     * 插件信息
     * @author jry <598821125@qq.com>
     */
    public $info = array(
        'name'        => 'RocketToTop',
        'title'       => '小火箭返回顶部',
        'description' => '小火箭返回顶部',
        'status'      => '1',
        'author'      => 'OpenCMF',
        'version'     => '1.3.0',
    );

    /**
     * 插件安装方法
     * @author jry <598821125@qq.com>
     */
    public function install()
    {
        return true;
    }

    /**
     * 插件卸载方法
     * @author jry <598821125@qq.com>
     */
    public function uninstall()
    {
        return true;
    }

    /**
     * 实现的PageFooter钩子方法
     * @author jry <598821125@qq.com>
     */
    public function PageFooter($param)
    {
        $addons_config = $this->getConfig();
        if ($addons_config['status']) {
            $this->display('rocket');
        }
    }
}

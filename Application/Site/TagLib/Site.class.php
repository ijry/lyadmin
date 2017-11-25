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
namespace Site\TagLib;

use lyf\template\TagLib;

/**
 * 标签库
 * @author jry <598821125@qq.com>
 */
class Site extends TagLib
{
    /**
     * 定义标签列表
     * @author jry <598821125@qq.com>
     */
    protected $tags = array(
        'breadcrumb'    => array('attr' => 'name,cid', 'close' => 1), //面包屑导航列表
        'slider_list'   => array('attr' => 'name,limit,page,order,key', 'close' => 1), //幻灯列表
        'category_list' => array('attr' => 'name,pid,limit,page', 'close' => 1), //栏目分类列表
        'article_list'  => array('attr' => 'name,cid,limit,page,order,child,cover,banner', 'close' => 1), //文章列表
        'flink_list'    => array('attr' => 'name,pid,limit,page,order', 'close' => 1), //友链列表
    );

    /**
     * 面包屑导航列表
     * @author jry <598821125@qq.com>
     */
    public function _breadcrumb($tag, $content)
    {
        $site_id = I('get.site_id');
        $name    = $tag['name'];
        $cid     = $tag['cid'];
        $parse   = '<?php ';
        $parse .= '$__PARENT_CATEGORY__ = D(\'Site/Category\')->getParentCategory(' . $cid . ');';
        $parse .= ' ?>';
        $parse .= '<volist name="__PARENT_CATEGORY__" id="' . $name . '">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }

    /**
     * 幻灯列表
     * @author jry <598821125@qq.com>
     */
    public function _slider_list($tag, $content)
    {
        $site_id = I('get.site_id');
        $name  = $tag['name'];
        $limit = $tag['limit'] ?: 10;
        $page  = $tag['page'] ?: 1;
        $order = $tag['order'] ?: 'sort desc,id desc';
        $key   = $tag['key'] ?: 'i';
        $parse = '<?php ';
        $parse .= '$map = array(); ';
        $parse .= '$map["status"] = array("eq", "1");';
        $parse .= '$__SLIDER_LIST__ = D("Site/Slider")->getList(' . $limit . ', ' . $page . ', "' . $order . '", $map);';
        $parse .= ' ?>';
        $parse .= '<volist name="__SLIDER_LIST__" id="' . $name . '" key="' . $key . '">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }

    /**
     * 栏目分类列表
     * @author jry <598821125@qq.com>
     */
    public function _category_list($tag, $content)
    {
        $site_id = I('get.site_id');
        $name    = $tag['name'];
        $pid     = $tag['pid'] ?: 0;
        $limit   = $tag['limit'] ?: 10;
        $page    = $tag['page'] ?: 1;
        $field   = true;
        $parse   = '<?php ';
        $parse .= '$__CATEGORYLIST__ = D("Site/Category")->getCategoryTree( ' . $pid . ', ' . $limit . ', ' . $page . ', ' . $field . ');';
        $parse .= ' ?>';
        $parse .= '<volist name="__CATEGORYLIST__" id="' . $name . '">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }

    /**
     * 文章列表
     * @author jry <598821125@qq.com>
     */
    public function _article_list($tag, $content)
    {
        $site_id = I('get.site_id');
        $name    = $tag['name'];
        $cid     = $tag['cid'] ?: '""';
        $limit   = $tag['limit'] ?: 10;
        $page    = $tag['page'] ?: 1;
        $order   = $tag['order'] ?: '';
        $child   = $tag['child'] ?: '';
        $cover   = $tag['cover'] ?: '';
        $cover_slider   = $tag['banner'] ?: '';
        $parse   = '<?php ';
        $parse .= '$map = array(); ';
        if ($cover) {
            $parse .= '$map["cover"] = array("neq", "");';
        }
        if ($cover_slider) {
            $parse .= '$map["banner"] = array("neq", "");';
        }
        $parse .= '$__ARTICLE_LIST__ = D("Site/Article")->getList(' . $cid . ', ' . $limit . ', ' . $page . ', "' . $order . '", "' . $child . '", $map);';
        $parse .= ' ?>';
        $parse .= '<volist name="__ARTICLE_LIST__" id="' . $name . '">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }

    /**
     * 友链列表
     * @author jry <598821125@qq.com>
     */
    public function _flink_list($tag, $content)
    {
        $site_id = I('get.site_id');
        $name    = $tag['name'];
        $pid     = $tag['pid'] ?: '0';
        $limit   = $tag['limit'] ?: 10;
        $page    = $tag['page'] ?: 1;
        $order   = $tag['order'] ?: '';
        $parse   = '<?php ';
        $parse .= '$map = array("status" => "1");';
        $parse .= '$__FLINK_LIST__ = D("Site/Flink")->getList(' . $pid . ', ' . $limit . ', ' . $page . ', "' . $order . '", $map);';
        $parse .= ' ?>';
        $parse .= '<volist name="__FLINK_LIST__" id="' . $name . '">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }
}

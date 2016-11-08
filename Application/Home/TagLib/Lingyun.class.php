<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Home\TagLib;

use Think\Template\TagLib;

/**
 * 标签库
 * @author jry <598821125@qq.com>
 */
class Lingyun extends TagLib
{
    /**
     * 定义标签列表
     * @author jry <598821125@qq.com>
     */
    protected $tags = array(
        'sql_query' => array('attr' => 'sql,result', 'close' => 0), //SQL查询
        'nav_list'  => array('attr' => 'name,pid,group', 'close' => 1), //导航列表
    );

    /**
     * SQL查询
     */
    public function _sql_query($tag, $content)
    {
        $sql    = $tag['sql'];
        $result = !empty($tag['result']) ? $tag['result'] : 'result';
        $parse  = '<?php $' . $result . ' = M()->query("' . $sql . '");';
        $parse .= 'if($' . $result . '):?>' . $content;
        $parse .= "<?php endif;?>";
        return $parse;
    }

    /**
     * 导航列表
     */
    public function _nav_list($tag, $content)
    {
        $name  = $tag['name'];
        $pid   = $tag['pid'] ?: 0;
        $group = $tag['group'] ?: 'main';
        $parse = '<?php ';
        $parse .= '$__NAV_LIST__ = D(\'Admin/Nav\')->getNavTree(' . $pid . ', "' . $group . '");';
        $parse .= ' ?>';
        $parse .= '<volist name="__NAV_LIST__" id="' . $name . '">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }
}

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
namespace Site\Model;

use Common\Model\Model;

/**
 * 默认模型
 * @author jry <598821125@qq.com>
 */
class IndexModel extends Model
{
    /**
     * 模块名称
     * @author jry <598821125@qq.com>
     */
    public $moduleName = 'Site';

    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'site_index';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('title', 'require', '网站名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_find(&$result, $options)
    {
        $result['logo_url']   = get_cover($result['logo']);
        $result['theme_info'] = D('Site/Theme')->find($result['theme']);

        // 站点首页
        $result['domain_url'] = $result['homepage'] = U('Site/Index/index/', '', true, true);
    }

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_select(&$result, $options)
    {
        foreach ($result as &$record) {
            $this->_after_find($record, $options);
        }
    }

    /**
     * 指定站点是否有数据
     * @author jry <598821125@qq.com>
     */
    public function has_data($site_id = '')
    {
        // 数据校验
        // if (!$site_id) {
        //     $this->error = '站点ID不能为空';
        //     return false;
        // }

        // 幻灯片
        $con = array();
        //$con['site_id'] = $site_id;
        if (D('Site/Slider')->where($con)->count() > 0) {
            return true;
        }

        // 分类
        $con = array();
        //$con['site_id'] = $site_id;
        if (D('Site/Category')->where($con)->count() > 0) {
            return true;
        }

        // 文章
        $con = array();
        //$con['site_id'] = $site_id;
        if (D('Site/Article')->where($con)->count() > 0) {
            return true;
        }

        // 文章
        $con = array();
        //$con['site_id'] = $site_id;
        if (D('Site/Article')->where($con)->count() > 0) {
            return true;
        }

        // 友情连接
        $con = array();
        //$con['site_id'] = $site_id;
        if (D('Site/Flink')->where($con)->count() > 0) {
            return true;
        }

        // 留言
        $con = array();
        //$con['site_id'] = $site_id;
        if (D('Site/Liuyan')->where($con)->count() > 0) {
            return true;
        }

        // 自定义表单
        $con = array();
        //$con['site_id'] = $site_id;
        if (D('Site/Form')->where($con)->count() > 0) {
            return true;
        }

        // 自定义表单数据
        $con = array();
        //$con['site_id'] = $site_id;
        if (D('Site/Data')->where($con)->count() > 0) {
            return true;
        }

        return false;
    }
}

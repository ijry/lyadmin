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
 * 文章模型
 * @author jry <598821125@qq.com>
 */
class ArticleModel extends Model
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
    protected $tableName = 'site_article';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('cid', 'require', '请选择文章分类', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', 'require', '请填写文章标题', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('content', 'require', '请填写文章内容', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
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
        $result['cover_url']          = get_cover($result['cover'], 'default');
        $result['banner_url']         = get_cover($result['banner']);
        $result['create_time_format'] = time_format($result['create_time'], 'Y-m-d H:i:s');
        $result['href']               = U('Site/Index/detail/', array('id' => $result['id']));
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
     * 获取文章列表
     * @author jry <598821125@qq.com>
     */
    public function getList($cid = '', $limit = 10, $page = 1, $order = null, $child = false, $is_recommend = '', $map = null)
    {
        //获取分类信息
        $category_object = D('Site/Category');
        $category_info   = $category_object->find($cid);

        // 获取相同文档类型的子分类
        if ($cid) {
            if ($child) {
                $child_cate_ids = $category_object->where(array('pid' => $cid))->getField('id', true);
                if ($child_cate_ids) {
                    $cid_list[] = $cid;
                    $cid_list   = array_merge($cid_list, $child_cate_ids);
                }
            } else {
                $cid_list[] = $cid;
            }
        }

        $con = array();
        if ($cid) {
            $con["cid"] = array("in", $cid_list);
        }
        $con["status"] = array("eq", '1');
        if ($map) {
            $con = array_merge($con, $map);
        }
        if (!$order) {
            $order = 'sort desc,create_time desc';
        }
        if ($is_recommend) {
            $con["is_recommend"] = $is_recommend;
        }
        $return_list = $this->page($page, $limit)->order($order)->where($con)->select();

        return $return_list;
    }

    /**
     * 获取标签和每个标签的文章数量
     */
    public function getTag(){
        $tag_object = D('Article');
        $data = $tag_object->where(['status'=>1])->getField("id,tags");
        $str = implode(",",$data);
        $arr = explode(",",$str);
        $data = array_count_values($arr);
        $tag_list = array();
        $array = array();
        foreach ($data as $key => $value) {
            $array['tag'] = $key;
            $array['count'] = $value;
            $array['href'] = U("Site/Index/lists/",array('tag'=>$key));
            $tag_list[] = $array;
        }
        return $tag_list;
    }

        /**
     * 获取文章详情
     * @author jry <598821125@qq.com>
     */
    public function detail($id, $map = null)
    {
        //获取基础表信息
        $con       = array();
        $con['id'] = $id;
        if ($map) {
            $con = array_merge($con, $map);
        }
        $info = $this->where($con)->find();
        if (!is_array($info)) {
            $this->error = '文章被禁用或已删除！';
            return false;
        }

        // 阅读量加1
        $result = $this->where(array('id' => $id))->SetInc('view_count');


        // 获取作者信息
        $info['user'] = D('Admin/User')->getUserInfo($info['uid']);

        // 获取发帖数量
        $info['user']['post_count'] = $this->where(array('uid' => $info['uid']))->count();

        // 获取评论数量
        $info['user']['comment_count'] = D($this->moduleName . '/Comment')->where(array('uid' => $info['uid']))->count();


        // 基础信息与扩展信息合并
        if (is_array($extend_data)) {
            $info = array_merge($info, $extend_data);
        }

        // 获取上一篇和下一篇文章信息
        $info['previous'] = $this->getPrevious($info);
        $info['next']     = $this->getNext($info);
        return $info;
    }

        /**
     * 获取当前分类上一篇文章
     * @author jry <598821125@qq.com>
     */
    private function getPrevious($info)
    {
        // 获取文档信息
        $map['status'] = array('eq', 1);
        $map['id']     = array('lt', $info['id']);
        $map['cid']    = array('eq', $info['cid']);
        $previous      = $this->where($map)->order('id desc')->find();

        if (!$previous) {
            $previous['title'] = '没有了';
            $previous['href']  = '#';
        } else {
            $previous['href'] = U($this->moduleName . '/index/detail', array('id' => $previous['id']));
        }
        return $previous;
    }

    /**
     * 获取当前分类下一篇文章
     * @author jry <598821125@qq.com>
     */
    private function getNext($info)
    {
        // 获取文档信息
        $map['status'] = array('eq', 1);
        $map['id']     = array('gt', $info['id']);
        $map['cid']    = array('eq', $info['cid']);
        $next          = $this->where($map)->order('id asc')->find();

        if (!$next) {
            $next['title'] = '没有了';
            $next['href']  = '#';
        } else {
            $next['href'] = U($this->moduleName . '/index/detail', array('id' => $next['id']));
        }
        return $next;
    }

}

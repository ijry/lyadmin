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
 * 分类模型
 * @author jry <598821125@qq.com>
 */
class CategoryModel extends Model
{
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'site_category';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('cate_type', 'require', '分类类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', 'require', '分类标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,32', '标题长度为1-32个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('is_show', '1', self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_find(&$result, $options)
    {
        $result['cover_url'] = get_cover($result['cover'], 'default');

        // U函数对域名路由支持不完善导致这里只能写绝对地址
        // 为了开发方便兼容localhost和127.0.0.1
        switch ($result['cate_type']) {
            case '0': // 文章列表
                $result['href'] = U('Site/Index/lists/', array('cid' => $result['id']));
                break;
            case '1': // 单页类型
                $result['href'] = U('Site/Index/page/', array('cid' => $result['id']));
                break;
            case '2': // 外链列表
                $result['href'] = $result['url'];
                break;
        }
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
     * 获取参数的所有父级分类
     * @param int $cid 分类id
     * @return array 参数分类和父类的信息集合
     * @author jry <598821125@qq.com>
     */
    public function getParentCategory($cid)
    {
        if (empty($cid)) {
            return false;
        }
        $con              = array();
        $con['status']    = 1;
        $category_list    = $this->where($con)->field(true)->select();
        $current_category = $this->field(true)->find($cid); //获取当前分类的信息
        $result[]         = $current_category;
        $pid              = $current_category['pid'];
        while (true) {
            foreach ($category_list as $key => $val) {
                if ($val['id'] == $pid) {
                    $pid = $val['pid'];
                    array_unshift($result, $val); //将父分类插入到数组第一个元素前
                }
            }
            if ($pid == 0 || count($result) == 1) {
                //已找到顶级分类或者没有任何父分类
                break;
            }
        }
        return $result;
    }

    /**
     * 获取分类树，指定分类则返回指定分类的子分类树，不指定则返回所有分类树，指定分类若无子分类则返回同级分类
     * @param  integer $id    分类ID
     * @param  boolean $field 查询字段
     * @return array          分类树
     * @author jry <598821125@qq.com>
     */
    public function getCategoryTree($id = 0, $limit = null, $page = 1, $field = true)
    {
        //获取当前分类信息
        if ((int) $id > 0) {
            $info = $this->find($id);
            $id   = $info['id'];
        }

        //获取所有分类
        $map            = array();
        $map['is_show'] = array('eq', '1');
        $map['status']  = array('eq', '1');
        $tree           = new \lyf\Tree();
        $list           = $this->field($field)->where($map)->order('sort asc, id asc')->select();

        // 转换成树结构
        $list = $tree->list2tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = (int) $id); //返回当前分类的子分类树
        if (!$list) {
            $list = $this->getSameLevelCategoryTree($id);
        }
        if ($limit) {
            $list = array_slice($list, ($page - 1) * $limit, $limit);
        }
        return $list;
    }

    /**
     * 获取同级分类树
     * @param  integer $id    分类ID
     * @return array          分类树
     * @author jry <598821125@qq.com>
     */
    public function getSameLevelCategoryTree($id)
    {
        //获取所有分类
        $info          = $this->find($id);
        $map           = array();
        $map['status'] = array('eq', 1);
        $map['pid']    = array('eq', $info['pid']);
        $list          = $this->field(true)->where($map)->order('sort asc')->select();

        return $list;
    }
}

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
namespace Admin\Model;

use Common\Model\Model;
use lyf\Tree;

/**
 * 导航模型
 * @author jry <598821125@qq.com>
 */
class NavModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'admin_nav';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('group', 'require', '导航分组必须', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('title', 'require', '导航标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('type', 'require', '导航类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('url', '0,255', '链接长度为0-25个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('value', 'get_value_by_type', self::MODEL_BOTH, 'callback'),
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
        // 处理不同导航类型
        switch ($result['type']) {
            case 'link':
                $result['url'] = $result['value'];
                if (!$result['url']) {
                    $result['href'] = C('HOME_PAGE');
                } else {
                    if (strpos($result['url'], "http") === 0) {
                        $result['href'] = $result['url'];
                    } else {
                        $result['href'] = U($result['url'], '', true, !C('IS_API'));
                    }
                }
                break;
            case 'module':
                $result['module_name'] = $result['value'];
                $result['href']        = U('/' . ucfirst($result['value']), '', false, !C('IS_API'));
                break;
            case 'page':
                $result['content'] = $result['value'];
                $result['href']    = U('Home/Nav/page', array('id' => $result['id']), true, !C('IS_API'));
                break;
            case 'post':
                $result['href'] = U('Home/Nav/lists', array('cid' => $result['id']), true, !C('IS_API'));
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
     * 导航类型
     * @author jry <598821125@qq.com>
     */
    public function nav_type($id = '')
    {
        $list['link']   = '链接';
        $list['module'] = '模块';
        $list['page']   = '单页';
        $list['post']   = '文章列表';
        return $id ? $list[$id] : $list;
    }

    /**
     * 根据导航类型获取值
     * @author jry <598821125@qq.com>
     */
    public function get_value_by_type($value = '')
    {
        if (!$value) {
            switch (I('post.type')) {
                case 'link':
                    return I('post.url');
                    break;
                case 'module':
                    return I('post.module_name');
                    break;
                case 'page':
                    return I('post.content');
                    break;
            }
        } else {
            return $value;
        }
    }

    /**
     * 获取参数的所有父级导航
     * @param int $id 导航id
     * @return array 参数导航和父类的信息集合
     * @author jry <598821125@qq.com>
     */
    public function getParentNav($id, $group = 'main')
    {
        if (empty($id)) {
            return false;
        }
        $con['status'] = 1;
        $con['group']  = array('eq', $group);
        $nav_list      = $this->where($con)->field(true)->order('sort asc,id asc')->select();
        $current_nav   = $this->field(true)->find($cid); //获取当前导航的信息
        $result[]      = $current_nav;
        $pid           = $current_nav['pid'];
        while (true) {
            foreach ($nav_list as $key => $val) {
                if ($val['id'] == $pid) {
                    $pid = $val['pid'];
                    array_unshift($result, $val); //将父导航插入到第一个元素前
                }
            }
            // 已找到顶级导航或者没有任何父导航
            if ($pid == 0 || count($result) == 1) {
                break;
            }
        }
        return $result;
    }

    /**
     * 获取导航树，指定导航则返回指定导航的子导航树，不指定则返回所有导航树，指定导航若无子导航则返回同级导航
     * @param  integer $id    导航ID
     * @param  boolean $field 查询字段
     * @return array          导航树
     * @author jry <598821125@qq.com>
     */
    public function getNavTree($id = 0, $group = 'main', $field = true)
    {
        // 开启默认模块并且使用默认模块布局
        if (C('DEFAULT_MODULE_LAYOUT') && C('DEFAULT_PUBLIC_LAYOUT') && is_file(APP_DIR . C('DEFAULT_MODULE') . '/Model/NavModel.class.php')) {
            $module_nav_tree = D(C('DEFAULT_MODULE') . '/Nav')->getNavTree($id, $group, $field);
            if ($module_nav_tree) {
                return $module_nav_tree;
            }
        }

        // 获取当前导航信息
        if ((int) $id > 0) {
            $info = $this->find($id);
            $id   = $info['id'];
        }
        // 获取所有导航
        $map['status'] = array('eq', 1);
        $map['group']  = array('eq', $group);
        $tree          = new Tree();
        $list          = $this->field($field)->where($map)->order('sort asc,id asc')->select();

        // 返回当前导航的子导航树
        $list = $tree->list2tree(
            $list,
            $pk = 'id',
            $pid = 'pid',
            $child = '_child',
            $root = (int) $id
        );
        if (!$list) {
            return $this->getSameLevelNavTree($id);
        }
        return $list;
    }

    /**
     * 获取导航树，指定导航则返回指定导航的子导航树，不指定则返回所有导航树，指定导航若无子导航则返回同级导航
     * @param  integer $id    导航ID
     * @param  boolean $field 查询字段
     * @return array          导航树
     * @author jry <598821125@qq.com>
     */
    public function getNavTreeChild($id = 0, $group = 'main', $field = true, $module = '')
    {
        if (!$module) {
            $module = request()->module();
        }
        if (!in_array($module, array('Home', 'Admin', 'Common', 'Install')) && is_file(APP_DIR . $module . '/Model/NavModel.class.php')) {
            $module_nav_tree = D($module . '/Nav')->getNavTree($id, $group, $field);
            if ($module_nav_tree) {
                return $module_nav_tree;
            }
        }
    }

    /**
     * 获取同级导航树
     * @param  integer $id 导航ID
     * @return array       导航树
     * @author jry <598821125@qq.com>
     */
    public function getSameLevelNavTree($id = 0, $group = 'main')
    {
        //获取当前导航信息
        if ((int) $id > 0) {
            $nav_info    = $this->find($id);
            $parent_info = $this->find($nav_info['pid']);
            $id          = $info['id'];
        }
        //获取所有导航
        $map['status'] = array('eq', 1);
        $map['group']  = array('eq', $group);
        $map['pid']    = array('eq', $nav_info['pid']);
        $tree          = new Tree();
        $list          = $this->field($field)->where($map)->order('sort asc,id asc')->select();
        return $list;
    }
}

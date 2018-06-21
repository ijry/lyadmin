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
 * 广告模型
 * @author jry <598821125@qq.com>
 */
class AdModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'site_ad';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('name', 'require', '名称必须', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '3,32', '用户名长度为3-32个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
        array('name', '/^(?!_)(?!\d)(?!.*?_$)[\w]+$/', '名称只可含有数字、字母、下划线且不以下划线开头结尾，不以数字开头！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('type', 'require', '类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
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
            case '1':
                $result['text'] = $result['value'];
                break;
            case '2':
                $result['picture'] = $result['value'];
                break;
            case '3':
                $result['code'] = $result['value'];
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
    public function ad_type($id)
    {
        $list['1'] = '文字广告';
        $list['2'] = '图片广告';
        $list['3'] = '代码广告';
        return $id ? $list[$id] : $list;
    }

    /**
     * 根据类型获取值
     * @author jry <598821125@qq.com>
     */
    public function get_value_by_type($value)
    {
        if (!$value) {
            switch (I('post.type')) {
                case '1':
                    return I('post.text');
                    break;
                case '2':
                    return I('post.picture');
                    break;
                case '3':
                    return I('post.code');
                    break;
            }
        } else {
            return $value;
        }
    }
}

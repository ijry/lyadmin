<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/28 0028
 * Time: 14:11
 */

namespace Site\Model;
use Common\Model\Model;
use lyf\Page;

/**
 * 评论模型
 * @author jry <598821125@qq.com>
 */
class CommentModel extends Model
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
    protected $tableName = 'site_comment';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('data_id', 'require', '数据ID', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('content', 'require', '内容不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('content', '1,1280', '内容长度不多于1280个字符', self::VALUE_VALIDATE, 'length'),
        array('content', 'checkContent', '至少包含2个中文字符', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('content', 'html2text', self::MODEL_BOTH, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', 1, self::MODEL_INSERT, 'string'),
        array('ip', 'get_client_ip', self::MODEL_INSERT, 'function'),
    );

    /**
     * 验证评论内容
     * @author jry <598821125@qq.com>
     */
    public function checkContent($map)
    {
        preg_match_all("/([\一-\龥]){1}/u", $_POST['content'], $num);
        if (2 > count($num[0])) {
            return false;
        }
        return true;
    }

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_find(&$result, $options)
    {
        $result['user']               = D('Admin/User')->getUserInfo($result['uid']);
        $result['create_time_format'] = friendly_date($result['create_time']);
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
     * 发表评论
     * @author jry <598821125@qq.com>
     */
    public function addNew($data)
    {
        $add_result = $this->add($data);
        if ($add_result) {
            //更新评论数
            $article_object = D($this->moduleName . '/Article');
            $article_object->where(array('id' => (int) $data['data_id']))->setInc('comment');

            //获取当前被评论文档的基础信息
            $current_document_info = $article_object->detail($data['data_id']);

            //查看详情连接
            $view_detail = '<a href="' . U($this->moduleName . '/Index/detail', array('id' => $current_document_info['id']), true, true) . '"> 查看详情... </a>';

            //当前发表评论的用户信息
            $uid               = is_login();
            $current_user_info = D('Admin/User')->getUserInfo($uid);

            //给评论用户用户名加上链接以便于直接点击
            $current_username = '<a href="' . U('User/Index/home', array('uid' => $current_user_info['id']), true, true) . '">' . $current_user_info['nickname'] . '</a>';

            //如果是对别人的评论进行回复则获取被评论的那个人的UID以便于发消息骚扰他
            if ($data['pid']) {
                $previous_comment_uid = D($this->moduleName . '/Comment')->getFieldById($data['pid'], 'uid');
            }

            //定义消息结构
            $msg_data['title']    = $current_username . '回复了您！' . $view_detail;
            $msg_data['type']     = 1;
            $msg_data['form_uid'] = $uid;

            //给文档作者发送消息
            //自己给自己发表的文档评论时不发送 要求$current_document_info['uid'] !== $current_user_info['id']
            if ($current_document_info['uid'] !== $current_user_info['id']) {
                //给文档发表者发消息
                $msg_data['to_uid'] = $current_document_info['uid'];
                $result              = D('User/Message')->sendMessage($msg_data);
            }

            //给被回复者发送消息
            //自己回复自己的评论时不发送 要求$current_document_info['uid'] !== $previous_comment_uid
            //如果是对别人的评论进行回复则获取被评论的那个人的UID以便于发消息骚扰他
            if ($data['pid']) {
                $previous_comment_uid    = D($this->moduleName . '/Comment')->getFieldById($data['pid'], 'uid');
                if ($current_document_info['uid'] !== $previous_comment_uid) {
                    $msg_data['to_uid'] = $previous_comment_uid;
                    $result              = D('User/Message')->sendMessage($msg_data);
                }
            }
        }
        return $add_result;
    }

    /**
     * 根据条件获取评论列表
     * @author jry <598821125@qq.com>
     */
    public function getCommentList($data_id = '', $limit = 10, $page = 1, $order = 'id desc', $con = null)
    {
        $map['status']  = 1;
        if ($data_id) {
            $map['data_id'] = $data_id;
        }
        if ($con) {
            $map = array_merge($map, $con);
        }
        $lists = $this->where($map)->page($page, $limit)->order($order)->select();
        $page  = new Page(
            $this->where($map)->count(),
            $limit
        );
        foreach ($lists as $key => &$val) {
            if ($val['pid'] > 0) {
                $parent_comment                 = $this->find($val['pid']);
                $val['parent_comment_nickname'] = $parent_comment['user']['nickname'];
                $return['href']  = U("Site/Index/detail/",array('id'=>$val['data_id']));
            }
        }
        $return['lists'] = $lists;
        $return['page']  = $page->show();
        return $return;
    }
}
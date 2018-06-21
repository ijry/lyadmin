<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/1 0001
 * Time: 9:58
 */

namespace Site\Controller;
use Home\Controller\HomeController;

/**
 * 评论控制器
 * Class CommentController
 * @package Site\Controller
 */
class CommentController extends HomeController
{
    public function index($data_id, $limit = 10, $page = 1, $order = '', $con = null){
        $comment_object = D('Comment');
        $list           = $comment_object->getCommentList($data_id,$limit,$page,$order,$con);
        $this->success('评论列表', '', array('data' => $list));
    }

    /**
     * 新增评论
     */
    public function add(){
        if (request()->isPost()){
            $uid            = $this->is_login();
            $comment_object = D(D('Index')->moduleName . '/Comment');
            $data           = $comment_object->create();
            if ($data) {
                $result = $comment_object->addNew($data);
                if ($result) {
                    $this->success('评论成功');
                } else {
                    $this->error('评论失败' . $comment_object->getError());
                }
            } else {
                $this->error($comment_object->getError());
            }
        }
    }
}
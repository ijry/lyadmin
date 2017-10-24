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
namespace Home\Controller;

/**
 * 上传控制器
 * @author jry <598821125@qq.com>
 */
class UploadController extends HomeController
{
    /**
     * 上传
     * @author jry <598821125@qq.com>
     */
    public function upload()
    {
        if (is_login() || (C('AUTH_KEY') === $_SERVER['HTTP_UPLOADTOKEN'])) {
            // 上传图片
            $return = json_encode(D('Admin/Upload')->upload());
            exit($return);
        } else {
            $this->error('游客不允许上传文件！');
        }
    }

    /**
     * 下载
     * @author jry <598821125@qq.com>
     */
    public function download($token)
    {
        $this->is_login();

        if (empty($token)) {
            $this->error('token参数错误！');
        }

        //解密下载token
        $file_md5 = \lyf\Crypt::decrypt($token, user_md5(is_login()));
        if (!$file_md5) {
            $this->error('下载链接已过期，请刷新页面！');
        }

        $upload_object = D('Admin/Upload');
        $file_id       = $upload_object->getFieldByMd5($file_md5, 'id');
        if (!$upload_object->download($file_id)) {
            $this->error($upload_object->getError());
        }
    }

    /**
     * KindEditor编辑器文件管理
     * @author jry <598821125@qq.com>
     */
    public function fileManager($only_image = true)
    {
        $uid = $this->is_login();
        exit(D('Admin/cUpload')->fileManager($only_image));
    }
}

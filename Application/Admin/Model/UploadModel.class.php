<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Model;

use Common\Model\ModelModel;
use Think\Upload;

/**
 * 上传模型
 * @author jry <598821125@qq.com>
 */
class UploadModel extends ModelModel
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'admin_upload';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('name', 'require', '文件名不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('path', 'require', '文件不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('size', 'require', '文件大小不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('md5', 'require', '文件Md5编码不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('sha1', 'require', '文件Sha1编码不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
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
        //获取上传文件的地址
        if ($result['url']) {
            $result['real_path'] = $result['url'];
        } else {
            if (C('STATIC_DOMAIN')) {
                $result['real_path'] = C('STATIC_DOMAIN') . $result['path'];
            } else {
                if (C('IS_API')) {
                    $result['real_path'] = C('TOP_HOME_PAGE') . $result['path'];
                } else {
                    $result['real_path'] = __ROOT__ . $result['path'];
                }
            }
        }
        if (in_array($result['ext'], array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
            $result['show'] = '<img class="picture" src="' . $result['real_path'] . '">';
        } else {
            $result['show'] = '<i class="fa fa-file-' . $result['ext'] . '"></i>';
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
     * 获取上传图片路径
     * @param  int $id 文件ID
     * @return string
     * @author jry <598821125@qq.com>
     */
    public function getCover($id = null, $type = null)
    {
        if ($id) {
            // 如果以http开头直接返回
            if (strpos($id, "http") === 0) {
                return $id;
            }
            $upload_info = $this->find($id);
            $url         = $upload_info['real_path'];
        }
        if (!isset($url)) {
            switch ($type) {
                case 'default': // 默认图片
                    $url = C('TMPL_PARSE_STRING.__HOME_IMG__') . '/default/default.gif';
                    break;
                case 'avatar': // 用户头像
                    $url = C('TMPL_PARSE_STRING.__HOME_IMG__') . '/default/avatar.png';
                    break;
                case 'qr_code': // qr_code
                    $url = C('TMPL_PARSE_STRING.__HOME_IMG__') . '/default/qr_code.png';
                    break;
                case 'qr_ios': // qr_ios
                    $url = C('TMPL_PARSE_STRING.__HOME_IMG__') . '/default/qr_ios.png';
                    break;
                case 'qr_android': // qr_android
                    $url = C('TMPL_PARSE_STRING.__HOME_IMG__') . '/default/qr_android.png';
                    break;
                case 'qr_weixin': // qr_weixin
                    $url = C('TMPL_PARSE_STRING.__HOME_IMG__') . '/default/qr_weixin.png';
                    break;
                default:
                    $url = '';
                    break;
            }
        }
        return $url;
    }

    /**
     * 获取上传文件信息
     * @param  int $id 文件ID
     * @return string
     * @author jry <598821125@qq.com>
     */
    public function getUploadInfo($id, $field)
    {
        $upload_info = $this->where('status = 1')->find($id);
        if ($field) {
            if (!$upload_info[$field]) {
                return $upload_info['id'];
            } else {
                return $upload_info[$field];
            }
        }
        return $upload_info;
    }

    /**
     * 上传文件
     * @author jry <598821125@qq.com>
     */
    public function upload($files)
    {
        // 获取文件信息
        $_FILES = $files ? $files : $_FILES;

        // 返回标准数据
        $return = array('error' => 0, 'success' => 1, 'status' => 1);
        $dir    = I('request.dir') ? I('request.dir') : 'image'; // 上传类型image、flash、media、file
        if (!in_array($dir, array('image', 'flash', 'media', 'file'))) {
            $return['error']   = 1;
            $return['success'] = 0;
            $return['status']  = 0;
            $return['message'] = '缺少上传参数！';
            return $return;
        }

        // 上传文件钩子，用于七牛云、又拍云等第三方文件上传的扩展
        hook('UploadFile', $dir);

        // 根据上传文件类型改变上传大小限制
        $upload_config = C('UPLOAD_CONFIG');
        if ($_GET['temp'] === 'true') {
            $upload_config['rootPath'] = './Runtime/';
        }
        $upload_driver = C('UPLOAD_DRIVER');
        if (!$upload_driver) {
            $return['error']   = 1;
            $return['success'] = 0;
            $return['status']  = 0;
            $return['message'] = '无效的文件上传驱动';
            return $return;
        }

        // 友情提醒
        $upload_max_filesize = substr(ini_get('upload_max_filesize'), 0, -1);
        $post_max_size       = substr(ini_get('post_max_size'), 0, -1);
        if ($post_max_size < $upload_max_filesize) {
            $return['error']   = 1;
            $return['success'] = 0;
            $return['status']  = 0;
            $return['message'] = '警告：php.ini里post_max_size值应该设置比upload_max_filesize大';
            return $return;
        }

        if ($dir == 'image') {
            if (C('UPLOAD_IMAGE_SIZE')) {
                if (C('UPLOAD_IMAGE_SIZE') > $upload_max_filesize) {
                    $return['error']   = 1;
                    $return['success'] = 0;
                    $return['status']  = 0;
                    $return['message'] = '警告：php.ini里upload_max_filesize值小于系统后台设置的图片上传大小';
                    return $return;
                }
                $upload_config['maxSize'] = C('UPLOAD_IMAGE_SIZE') * 1024 * 1024; // 图片的上传大小限制
            }
        } else {
            if (C('UPLOAD_FILE_SIZE')) {
                if (C('UPLOAD_FILE_SIZE') > $upload_max_filesize) {
                    $return['error']   = 1;
                    $return['success'] = 0;
                    $return['status']  = 0;
                    $return['message'] = '警告：php.ini里upload_max_filesize值小于系统后台设置的文件上传大小';
                    return $return;
                }
                $upload_config['maxSize'] = C('UPLOAD_FILE_SIZE') * 1024 * 1024; // 普通文件上传大小限制
            }
        }

        // 上传配置
        $ext_arr = array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb', 'mp4'),
            'file'  => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'wps', 'txt', 'zip', 'rar', 'gz', 'bz2', '7z', 'ipa', 'apk', 'dmg', 'iso'),
        );

        // 计算文件散列以查看是否已有相同文件上传过
        $reay_file   = array_shift($_FILES);
        $con['md5']  = md5_file($reay_file['tmp_name']);
        $con['sha1'] = sha1_file($reay_file['tmp_name']);
        $con['size'] = $reay_file['size'];
        $upload      = $this->where($con)->find();
        if ($upload) {
            // 发现相同文件直接返回
            $return['id']   = $upload['id'];
            $return['name'] = $upload['name'];
            $return['url']  = $upload['real_path'];
            $return['path'] = '.' . $upload['path'];
        } else {
            // 上传文件
            $upload_config['removeTrash'] = array($this, 'removeTrash');
            $upload                       = new Upload($upload_config, $upload_driver, C("UPLOAD_{$upload_driver}_CONFIG")); // 实例化上传类
            $upload->exts                 = $ext_arr[$dir] ? $ext_arr[$dir] : $ext_arr['image']; // 设置附件上传允许的类型，注意此处$dir为空时漏洞
            $info                         = $upload->uploadOne($reay_file); // 上传文件
            if (!$info) {
                $return['error']   = 1;
                $return['success'] = 0;
                $return['status']  = 0;
                $return['message'] = '上传出错' . $upload->getError();
            } else {
                // 获取上传数据
                if ($_GET['temp'] === 'true') {
                    $upload_data['name'] = $info["name"];
                    $upload_data['path'] = '/Runtime/' . $info['savepath'] . $info['savename'];
                    $upload_data['url']  = $info["url"] ?: '';

                    // 返回数据
                    if ($upload_data["url"]) {
                        $return['url'] = $upload_data['url'];
                    } else {
                        $return['url'] = __ROOT__ . $upload_data['path'];
                    }
                    $return['path'] = '.' . $upload_data['path'];
                    $return['name'] = $upload_data['name'];
                } else {
                    $upload_data['type']     = $info["type"];
                    $upload_data['name']     = $info["name"];
                    $upload_data['path']     = '/Uploads/' . $info['savepath'] . $info['savename'];
                    $upload_data['url']      = $info["url"] ?: '';
                    $upload_data['ext']      = $info["ext"];
                    $upload_data['size']     = $info["size"];
                    $upload_data['md5']      = $info['md5'];
                    $upload_data['sha1']     = $info['sha1'];
                    $upload_data['location'] = $upload_driver;

                    // 返回数据
                    $result = $this->create($upload_data);
                    $result = $this->add($result);
                    if ($result) {
                        if ($info["url"]) {
                            $return['url'] = $upload_data['url'];
                        } else {
                            $return['url'] = __ROOT__ . $upload_data['path'];
                        }
                        $return['path'] = '.' . $upload_data['path'];
                        $return['name'] = $upload_data['name'];
                        $return['id']   = $result;
                    } else {
                        $return['error']   = 1;
                        $return['success'] = 0;
                        $return['status']  = 0;
                        $return['message'] = '上传出错' . $this->error;
                    }
                }

            }
        }
        return $return;
    }

    /**
     * 下载指定文件
     * @param  number  $root 文件存储根目录
     * @param  integer $id   文件ID
     * @param  string  $args 回调函数参数
     * @return boolean false-下载失败，否则输出下载文件
     */
    public function download($id, $callback = null, $args = null)
    {
        // 获取下载文件信息
        $file = $this->find($id);
        if (!$file) {
            $this->error = '不存在该文件！';
            return false;
        }
        // 下载文件
        switch ($file['location']) {
            case 'Local': // 下载本地文件
                return $this->downLocalFile($file, $callback, $args);
            default:
                $this->error = '不支持的文件存储类型！';
                return false;
        }
    }

    /**
     * 下载本地文件
     * @param  array    $file     文件信息数组
     * @param  callable $callback 下载回调函数
     * @param  string   $args     回调函数参数
     * @return boolean            下载失败返回false
     */
    private function downLocalFile($file, $callback = null, $args = null)
    {
        $fiel_path = '.' . $file['path'];
        if (file_exists($fiel_path)) {
            // 调用回调函数
            is_callable($callback) && call_user_func($callback, $args);

            // 新增下载数
            $this->where(array('id' => $file['id']))->setInc('download');

            // 执行下载
            header("Content-Description: File Transfer");
            header('Content-type: ' . $file['type']);
            header('Content-Length:' . $file['size']);
            if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
                // for IE
                header('Content-Disposition: attachment; filename="' . rawurlencode($file['name']) . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
            }
            readfile($fiel_path);
            exit;
        } else {
            $this->error = '文件已被删除！';
            return false;
        }
    }
}

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

/**
 * 上传模型
 * @author jry <598821125@qq.com>
 */
class UploadModel extends Model
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
        array('path', 'require', '路径不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('path', '', '该文件已存在', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),
        array('url', '', 'URL已存在', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),
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
        // 获取文件地址
        if ($result['url']) {
            $result['url_result'] = $result['url'];
        } else {
            $result['url_result'] = C('TOP_HOME_PAGE') . $result['path'];
        }
        if (in_array($result['ext'], array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
            $result['show'] = '<img class="picture" src="' . $result['url_result'] . '">';
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
            // 如果不是数字直接返回
            if (!is_numeric($id)) {
                // 如果是http开头说明存储了一个远程图片
                if (strpos($id, "http") === 0) {
                    return $id;
                } else {
                    foreach (C('TMPL_PARSE_STRING') as $key => $val) {
                        $id = preg_replace('/' . $key . '/', ltrim($val, '.'), $id);
                    }
                    if (strpos($id, "http") === 0) {
                        return $id;
                    } else {
                        return C('TOP_HOME_PAGE') . $id;
                    }
                }
            }
            $upload_info = $this->find($id);
            //获取上传文件的地址
            if ($upload_info['url']) {
                $url = $upload_info['url'];
            } else {
                if (C('STATIC_DOMAIN')) {
                    $url = C('STATIC_DOMAIN') . $upload_info['path'];
                } else {
                    $url = C('TOP_HOME_PAGE') . $upload_info['path'];
                }
            }
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
                case 'qr_weixin_app': // qr_weixin
                    $url = C('TMPL_PARSE_STRING.__HOME_IMG__') . '/default/qr_weixin_app.png';
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
     * curl模拟上传文件
     * @author jry <598821125@qq.com>
     */
    public function curlUploadFile($url, $filename, $formname = 'file')
    {
        // curl
        $ch = curl_init();
        if (class_exists('\CURLFile')) {
            $post_data = array(
                $formname => new \CURLFile(realpath($filename)),
            );
        } else {
            $post_data = array(
                $formname => '@' . realpath($filename),
            );
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('uploadtoken: ' . C('AUTH_KEY')));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        if ($result['status'] == '1') {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 裁剪图片
     * @author jry <598821125@qq.com>
     */
    public function crop($data = null)
    {
        $image = new \lyf\Image();
        if (strpos($data['src'], ".") !== 0) {
            $data['src'] = '.' . $data['src'];
        }
        $image->open($data['src']);
        $type = $image->type();
        if ($image) {
            $file = './Uploads/temp/' . \lyf\Str::randString(12, 1) . '.' . $type;
            $url  = U(MODULE_MARK . "/Upload/upload", array('module_name' => request()->module()), true, true);

            // 图片缩放计算
            $sw = $sh = 1;
            if ($data['vw']) {
                $sw = $image->width() / $data['vw'];
            }
            if ($data['vh']) {
                $sh = $image->height() / $data['vh'];
            }

            // 裁剪并保存
            $image->crop($data['w'] * $sw, $data['h'] * $sh, $data['x'] * $sh, $data['y'] * $sh)->save($file);
            $result = $this->curlUploadFile($url, $file);
            return $result;
        }
    }

    /**
     * 上传文件
     * @author jry <598821125@qq.com>
     */
    public function upload($files = null)
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

        // 上面hook是控制全局文件上传驱动的，有的时候我们需要整个网站全局驱动还是存储在本地
        // 而个别模块的功能需要将图片存储在托管服务可以使用此参数，反过来有的时候我们需要把
        // 附件默认驱动设为托管存储，但是有些附件需要存储在本地需要单独指定
        if (I('request.upload_driver') && exist_addon('Upload')) {
            $upload_addon = D('Addons://Upload/Upload');
            $result       = $upload_addon->setDriver(I('request.upload_driver'));
            if (!$result) {
                $this->error = '上传失败：' . $upload_addon->getError();
                return false;
            }
        }

        // 根据上传文件类型改变上传大小限制
        $upload_config = C('UPLOAD_CONFIG');
        // 自动创建上传根目录
        if (!is_dir($upload_config['rootPath'])) {
            mkdir($upload_config['rootPath'], 0700);
        }
        $module_name = $_GET['module_name'];
        if ($module_name) {
            $upload_config['rootPath'] = $upload_config['rootPath'] . strtolower($module_name) . '/';
            if (!is_dir($upload_config['rootPath'])) {
                mkdir($upload_config['rootPath'], 0700);
            }
        }
        $upload_config['savePath'] = $dir . '/'; // 保存子目录

        // temp模式只上传文件不保存到admin_upload表数据
        if ($_GET['temp'] === 'true') {
            $upload_config['rootPath'] = $upload_config['rootPath'] . 'temp/';
            if (!is_dir($upload_config['rootPath'])) {
                mkdir($upload_config['rootPath'], 0700);
            }
        }

        // 获取上传驱动
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
            'file'  => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'wps', 'txt', 'zip', 'rar', 'gz', 'bz2', '7z', 'ipa', 'apk', 'dmg', 'iso', 'pem', 'p12'),
        );

        // 获取出文件数组
        $reay_file = array_shift($_FILES);

        // 计算文件散列以查看是否已有相同文件上传过
        if (!isset($_GET['temp'])) {
            $con['md5']  = md5_file($reay_file['tmp_name']);
            $con['sha1'] = sha1_file($reay_file['tmp_name']);
            $con['size'] = $reay_file['size'];
            $upload      = $this->where($con)->find();
            if ($upload) {
                // 发现相同文件直接返回
                $return['id']   = $upload['id'];
                $return['name'] = $upload['name'];
                $return['path'] = '.' . $upload['path'];

                //获取上传文件的地址
                if ($upload['url']) {
                    $return['url'] = $upload['url'];
                } else {
                    $return['url'] = __ROOT__ . $upload['path'];
                }
                return $return;
            }
        }

        // 开始上传文件
        $upload_config['removeTrash'] = array($this, 'removeTrash');
        $upload                       = new \lyf\Upload($upload_config, $upload_driver, C("UPLOAD_{$upload_driver}_CONFIG")); // 实例化上传类
        $upload->exts                 = $ext_arr[$dir] ? $ext_arr[$dir] : $ext_arr['image']; // 设置附件上传允许的类型，注意此处$dir为空时漏洞
        $info                         = $upload->uploadOne($reay_file); // 上传文件
        if (!$info) {
            $return['error']   = 1;
            $return['success'] = 0;
            $return['status']  = 0;
            $return['message'] = '上传出错' . $upload->getError();
        } else {
            // 获取上传数据
            if (isset($_GET['temp']) && $_GET['temp'] === 'true') {
                // 采用临时上传模式，文件不会在数据表admin_upload留下记录
                $upload_data['name'] = $info["name"];
                $upload_data['path'] = $upload_config['rootPath'] . $info['savepath'] . $info['savename'];
                $upload_data['url']  = $info["url"] ?: '';

                // 返回数据
                if ($upload_data["url"]) {
                    $return['url']  = $upload_data['url'];
                    $return['path'] = $return['url'];
                } else {
                    $return['url']  = __ROOT__ . ltrim($upload_data['path'], '.');
                    $return['path'] = ltrim($upload_data['path'], '.');
                }
                $return['name'] = $upload_data['name'];
            } else {
                $upload_data['type'] = $info["type"];
                $upload_data['name'] = $info["name"];
                $upload_data['path'] = ltrim($upload_config['rootPath'], '.') . $info['savepath'] . $info['savename'];
                if ($info["url"]) {
                    $upload_data['url'] = $info["url"];
                }
                $upload_data['ext']      = $info["ext"];
                $upload_data['size']     = $info["size"];
                $upload_data['md5']      = $info['md5'];
                $upload_data['sha1']     = $info['sha1'];
                $upload_data['location'] = $upload_driver;

                // 返回数据
                $result = $this->create($upload_data);
                if ($result) {
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
                        $return['message'] = '记录上传文件信息出错' . $this->error;
                    }
                } else {
                    $return['error']   = 1;
                    $return['success'] = 0;
                    $return['status']  = 0;
                    $return['message'] = '文件信息验证出错' . $this->error;
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

    /**
     * KindEditor编辑器文件管理
     * @author jry <598821125@qq.com>
     */
    public function fileManager($only_image = false)
    {
        // 根目录路径，可以指定绝对路径，比如 /var/www/attached/
        $root_path = './Uploads/';

        // 根目录URL，可以指定绝对路径，比如 http://www.yoursite.com/attached/
        $root_url = __ROOT__ . '/Uploads';

        // 图片扩展名
        $ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');

        if ($dir_name !== '') {
            $root_path .= $dir_name . "/";
            $root_url .= $dir_name . "/";
            if (!file_exists($root_path)) {
                mkdir($root_path);
            }
        }

        // 根据path参数，设置各路径和URL
        if (empty($_GET['path'])) {
            $current_path     = realpath($root_path) . '/';
            $current_url      = $root_url;
            $current_dir_path = '';
            $moveup_dir_path  = '';
        } else {
            $current_path     = realpath($root_path) . '/' . $_GET['path'];
            $current_url      = $root_url . $_GET['path'];
            $current_dir_path = $_GET['path'];
            $moveup_dir_path  = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
        }

        // 排序形式，name or size or type
        $order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);

        // 不允许使用..移动到上一级目录
        if (preg_match('/\.\./', $current_path)) {
            echo 'Access is not allowed.';
            exit;
        }
        // 最后一个字符不是/
        if (!preg_match('/\/$/', $current_path)) {
            echo 'Parameter is not valid.';
            exit;
        }
        // 目录不存在或不是目录
        if (!file_exists($current_path) || !is_dir($current_path)) {
            echo 'Directory does not exist.';
            exit;
        }

        // 遍历目录取得文件信息
        $file_list = array();
        if ($handle = opendir($current_path)) {
            $i = 0;
            while (false !== ($filename = readdir($handle))) {
                if ($filename{0} == '.') {
                    continue;
                }

                $file = $current_path . $filename;
                if (is_dir($file)) {
                    $file_list[$i]['is_dir']   = true; //是否文件夹
                    $file_list[$i]['has_file'] = (count(scandir($file)) > 2); // 文件夹是否包含文件
                    $file_list[$i]['filesize'] = 0; // 文件大小
                    $file_list[$i]['is_photo'] = false; // 是否图片
                    $file_list[$i]['filetype'] = ''; // 文件类别，用扩展名判断
                } else {
                    $file_list[$i]['is_dir']   = false;
                    $file_list[$i]['has_file'] = false;
                    $file_list[$i]['filesize'] = filesize($file);
                    $file_list[$i]['dir_path'] = '';
                    $file_ext                  = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
                    $file_list[$i]['filetype'] = $file_ext;
                }
                if ($only_image === true
                    && $file_list[$i]['is_dir'] === false
                    && $file_list[$i]['is_photo'] === false) {
                    unset($file_list[$i]);
                } else {
                    $file_list[$i]['filename'] = $filename; // 文件名，包含扩展名
                    $file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); // 文件最后修改时间
                    $i++;
                }
            }
            closedir($handle);
        }

        usort($file_list, 'cmp_func');

        $result = array();

        // 相对于根目录的上一级目录
        $result['moveup_dir_path'] = $moveup_dir_path;

        // 相对于根目录的当前目录
        $result['current_dir_path'] = $current_dir_path;

        // 当前目录的URL
        $result['current_url'] = $current_url;

        // 文件数
        $result['total_count'] = count($file_list);

        // 文件列表数组
        $result['file_list'] = $file_list;

        // 返回JSON数据
        return json_encode($result);
    }
}

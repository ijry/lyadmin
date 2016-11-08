<?php
/**
 * PHP SDK for tietuku.com
 *
 * @author Tears <i@ltteam.cn>, qakcn <qakcnyn@gmail.com>
 */

/**
 * 贴图库 Token 生成类
 *
 * 生成机制说明请大家参考贴图库开放平台文档：{@link http://open.tietuku.com/doc#safe-token}
 *
 * @package TieTuKu
 * @author Tears, qakcn
 * @version 1.0
 */
class TieTuKuToken
{
    /**
     * @ignore
     */
    public $accesskey;
    /**
     * @ignore
     */
    public $secretkey;
    /**
     * @ignore
     */
    private $base64param;
    /**
     * 构造函数
     *
     * @access public
     * @param mixed $accesskey 贴图库平台accesskey
     * @param mixed $secretkey 贴图库平台secretkey
     * @return void
     */
    public function __construct($accesskey, $secretkey)
    {
        if ($accesskey == '' || $secretkey == '') {
            return false;
        }

        $this->accesskey = $accesskey;
        $this->secretkey = $secretkey;
    }
    /**
     * 将参数进行JSON格式化并且进行url安全的base64编码
     *
     * @param array $param 接口所需要的参数
     * @return mixed 返回该类 可进行连续操作
     */
    public function dealParam($param)
    {
        $this->base64param = $this->URLSafeBase64Encode(json_encode($param));
        return $this;
    }
    /**
     * 生成Token方法
     * 需要先调用dealParam方法否则返回false
     *
     * @return string 成功生成的Token 失败返回false
     */
    public function createToken()
    {
        if (empty($this->base64param)) {
            return false;
        }

        $sign = $this->signEncode($this->base64param, $this->secretkey);
        return $this->accesskey . ':' . $this->URLSafeBase64Encode($sign) . ':' . $this->base64param;
    }
    /**
     * Token hash加密方法
     *
     * @param string $str 需要进行hash加密的字符串
     * @param string $key secretkey
     * @return string hash_hmac sha1 加密后的字符串
     */
    public function signEncode($str, $key)
    {
        $hmac_sha1_str = "";
        if (function_exists('hash_hmac')) {
            $hmac_sha1_str = hash_hmac("sha1", $str, $key, true);
        } else {
            $blocksize = 64;
            $hashfunc  = 'sha1';
            if (strlen($key) > $blocksize) {
                $key = pack('H*', $hashfunc($key));
            }
            $key           = str_pad($key, $blocksize, chr(0x00));
            $ipad          = str_repeat(chr(0x36), $blocksize);
            $opad          = str_repeat(chr(0x5c), $blocksize);
            $hmac_sha1_str = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $str))));
        }
        return $hmac_sha1_str;
    }
    /**
     * url安全的base64编码 URLSafeBase64Encode
     *
     * @param string $str 需要进行url安全的base64编码的字符串
     * @return string 返回url安全的base64编码字符串
     */
    public function URLSafeBase64Encode($str)
    {
        $find    = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($str));
    }
}
/**
 * 贴图库 客户端操作类
 *
 *
 * @package TieTuKu
 * @author Tears
 * @version 1.0
 */
class TTKClient
{

    /**
     * Set up the API root URL.
     *
     * @ignore
     */
    public $upload_host = "http://up.tietuku.com/";
    public $host        = "http://api.tietuku.com/v1/";
    /**
     * Set timeout default.
     *
     * @ignore
     */
    public $timeout = 60;
    /**
     * Set CURL timeout.
     *
     * @ignore
     */
    public $CURLtimeout = 30;
    /**
     * 构造函数
     *
     * @access public
     * @param mixed $accesskey 贴图库平台accesskey
     * @param mixed $secretkey 贴图库平台secretkey
     * @return void
     */
    public function __construct($accesskey, $secretkey)
    {
        $this->op_Token = new \TieTuKuToken($accesskey, $secretkey);
    }
    /**
     * 查询随机30张推荐的图片
     *
     * 对应API：{@link http://open.tietuku.com/doc#list-getrandrec}
     *
     * @access public
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function getRandRec($createToken = false)
    {
        $url               = $this->host . "/List/";
        $param['deadline'] = time() + $this->timeout;
        $param['action']   = 'getrandrec';
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 根据类型ID查询随机30张推荐的图片
     *
     * 对应API：{@link http://open.tietuku.com/doc#list-getrandrec}
     *
     * @access public
     * @param int $cid 类型ID。
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function getRandRecByCid($cid, $createToken = false)
    {
        $url               = $this->host . "/List/";
        $param['deadline'] = time() + $this->timeout;
        $param['action']   = 'getrandrec';
        $param['cid']      = $cid;
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 根据 图片ID 查询相应的图片详细信息
     *
     * 对应API：{@link http://open.tietuku.com/doc#pic-getonepic}
     *
     * @access public
     * @param int $id 图片ID。
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function getOnePicById($id, $createToken = false)
    {
        $url               = $this->host . "/Pic/";
        $param['deadline'] = time() + $this->timeout;
        $param['action']   = 'getonepic';
        $param['id']       = $id;
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 根据 图片find_url 查询相应的图片详细信息
     *
     * 对应API：{@link http://open.tietuku.com/doc#pic-getonepic}
     *
     * @access public
     * @param string $find_url 图片find_url
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function getOnePicByFind_url($find_url, $createToken = false)
    {
        $url               = $this->host . "/Pic/";
        $param['deadline'] = time() + $this->timeout;
        $param['action']   = 'getonepic';
        $param['findurl']  = $find_url;
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 分页查询全部图片列表 每页30张图片
     *
     * 对应API：{@link http://open.tietuku.com/doc#list-getnewpic}
     *
     * @access public
     * @param int $page_no 页数，默认为1。
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function getNewPic($page_no = 1, $createToken = false)
    {
        $url               = $this->host . "/List/";
        $param['deadline'] = time() + $this->timeout;
        $param['action']   = 'getnewpic';
        $param['page_no']  = $page_no;
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 通过类型ID分页查询全部图片列表 每页30张图片
     *
     * 对应API：{@link http://open.tietuku.com/doc#list-getnewpic}
     *
     * @access public
     * @param int $cid 类型ID。
     * @param int $page_no 页数，默认为1。
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function getNewPicByCid($cid, $page_no = 1, $createToken = false)
    {
        $url               = $this->host . "/List/";
        $param['deadline'] = time() + $this->timeout;
        $param['action']   = 'getnewpic';
        $param['cid']      = $cid;
        $param['page_no']  = $page_no;
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 根据用户ID查询用户相册列表
     *
     * 对应API：{@link http://open.tietuku.com/doc#album-get}
     *
     * @access public
     * @param int $uid 用户ID
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function getAlbumByUid($uid = null, $createToken = false)
    {
        $url               = $this->host . "/Album/";
        $param['deadline'] = time() + $this->timeout;
        $param['action']   = 'get';
        if (!empty($uid)) {
            $param['uid'] = $uid;
        }

        $Token         = $this->op_Token->dealParam($param)->createToken();
        $data['Token'] = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 查询自己收藏的图片列表
     *
     * 对应API：{@link http://open.tietuku.com/doc#collect-getlovepic}
     *
     * @access public
     * @param int $page_no 页数，默认为1。
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function getLovePic($page_no = 1, $createToken = false)
    {
        $url               = $this->host . "/Collect/";
        $param['deadline'] = time() + $this->timeout;
        $param['action']   = 'getlovepic';
        $param['page_no']  = $page_no;
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 通过图片ID喜欢(收藏)图片
     *
     * 对应API：{@link http://open.tietuku.com/doc#collect-addcollect}
     *
     * @access public
     * @param int $id 图片ID。
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function addCollect($id, $createToken = false)
    {
        $url               = $this->host . "/Collect/";
        $param['deadline'] = time() + $this->timeout;
        $param['action']   = 'addcollect';
        $param['id']       = $id;
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 通过图片ID取消喜欢(取消收藏)图片
     *
     * 对应API：{@link http://open.tietuku.com/doc#collect-delcollect}
     *
     * @access public
     * @param int $id 图片ID。
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function delCollect($id, $createToken = false)
    {
        $url               = $this->host . "/Collect/";
        $param['deadline'] = time() + $this->timeout;
        $param['action']   = 'delcollect';
        $param['id']       = $id;
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 通过相册ID分页查询相册中的图片 每页30张图片
     *
     * 对应API：{@link http://open.tietuku.com/doc#list-album}
     *
     * @access public
     * @param int $aid 相册ID。
     * @param int $page_no 页数，默认为1。
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */

    public function getAlbumPicByAid($aid, $page_no = 1, $createToken = false)
    {
        $url               = $this->host . "/List/";
        $param['deadline'] = time() + $this->timeout;
        $param['action']   = 'album';
        $param['aid']      = $aid;
        $param['page_no']  = $page_no;
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 查询所有的分类
     *
     * 对应API：{@link http://open.tietuku.com/doc#catalog-getall}
     *
     * @access public
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function getCatalog($createToken = false)
    {
        $url               = $this->host . "/Catalog/";
        $param['deadline'] = time() + $this->timeout;
        $param['action']   = 'getall';
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 创建相册
     *
     * 对应API：{@link http://open.tietuku.com/doc#album-create}
     *
     * @access public
     * @param string $albumname 相册名称。
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function createAlbum($albumname, $createToken = false)
    {
        $url                = $this->host . "/Album/";
        $param['deadline']  = time() + $this->timeout;
        $param['action']    = 'create';
        $param['albumname'] = $albumname;
        $Token              = $this->op_Token->dealParam($param)->createToken();
        $data['Token']      = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 编辑相册
     *
     * 对应API：{@link http://open.tietuku.com/doc#album-editalbum}
     *
     * @access public
     * @param int $aid 相册ID。
     * @param string $albumname 相册名称。
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function editAlbum($aid, $albumname, $createToken = false)
    {
        $url                = $this->host . "/Album/";
        $param['deadline']  = time() + $this->timeout;
        $param['action']    = 'editalbum';
        $param['aid']       = $aid;
        $param['albumname'] = $albumname;
        $Token              = $this->op_Token->dealParam($param)->createToken();
        $data['Token']      = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 通过相册ID删除相册(只能删除自己的相册 如果只有一个相册，不能删除)
     *
     * 对应API：{@link http://open.tietuku.com/doc#album-delalbum}
     *
     * @access public
     * @param int $aid 相册ID。
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function delAlbum($aid, $createToken = false)
    {
        $url               = $this->host . "/Album/";
        $param['deadline'] = time() + $this->timeout;
        $param['action']   = 'delalbum';
        $param['aid']      = $aid;
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 通过一组图片ID 查询图片信息
     *
     * 对应API：{@link http://open.tietuku.com/doc#list-getpicbyids}
     *
     * @access public
     * @param mix $ids 图片ID数组。(1.多个ID用逗号隔开 2.传入数组)
     * @param boolean $createToken 是否只返回Token，默认为false。
     * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
     */
    public function getPicByIds($ids, $createToken = false)
    {
        $stringid = '';
        if (is_array($ids)) {
            foreach ($ids as $k => $v) {
                $stringid .= $v . ',';
            }
            $stringid = substr($stringid, 0, -1);
        } else {
            $stringid = $ids;
        }
        $url               = $this->host . "/List/";
        $param['deadline'] = time() + $this->timeout;
        $param['action']   = 'getpicbyids';
        $param['ids']      = $stringid;
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        return $createToken ? $Token : $this->post($url, $data);
    }
    /**
     * 上传单个文件到贴图库
     *
     * 对应API：{@link http://open.tietuku.com/doc#upload}
     *
     * @access public
     * @param int $aid 相册ID
     * @param array $file 上传的文件。
     * @return string 如果$file!=null 返回请求接口的json数据否则只返回Token
     */
    public function uploadFile($aid, $file = null, $filename = null)
    {
        $url               = $this->upload_host;
        $param['deadline'] = time() + $this->timeout;
        $param['aid']      = $aid;
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        $data['file']      = '@' . $file . (empty($filename) ? '' : (';filename=' . $filename));
        return empty($file) ? $Token : $this->post($url, $data);
    }
    /**
     * 上传多个文件到贴图库
     *
     * 对应API：{@link http://open.tietuku.com/doc#upload}
     *
     * @access public
     * @param int $aid 相册ID
     * @param string $filename 文件域名字
     * @return mixed 返回请求接口的json 如果文件域不存在文件则返回NULL
     */
    public function curlUpFile($aid, $filename)
    {
        if (is_array($_FILES[$filename]['tmp_name'])) {
            foreach ($_FILES[$filename]['tmp_name'] as $k => $v) {
                if (!empty($v)) {
                    $userfile = $_FILES[$filename]['name'][$k];
                    $res[]    = json_decode($this->uploadFile($aid, $v, $userfile));
                }
            }
        } else {
            $res = json_decode($this->uploadFile($aid, $_FILES[$filename]['tmp_name'], $_FILES[$filename]['name']));
        }
        return json_encode($res);
    }
    /**
     * 上传网络文件到贴图库 (只支持单个连接)
     *
     * 对应API：{@link http://open.tietuku.com/doc#upload-url}
     *
     * @access public
     * @param int $aid 相册ID
     * @param string $fileurl 网络图片地址
     * @return string 如果$fileurl!=null 返回请求接口的json数据否则只返回Token
     */
    public function uploadFromWeb($aid, $fileurl = null)
    {
        $url               = $this->upload_host;
        $param['deadline'] = time() + $this->timeout;
        $param['aid']      = $aid;
        $param['from']     = 'web';
        $Token             = $this->op_Token->dealParam($param)->createToken();
        $data['Token']     = $Token;
        $data['fileurl']   = $fileurl;
        return empty($fileurl) ? $Token : $this->post($url, $data);
    }
    /**
     * 对接口post数据
     *
     *
     * @access public
     * @param string $url 接口请求地址。
     * @param array $data 需要post的数据
     * @return string 返回的json数据
     */
    public function post($url, $post_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->CURLtimeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

}

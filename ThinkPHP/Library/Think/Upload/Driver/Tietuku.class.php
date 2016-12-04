<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <http://www.code-tech.diandian.com>
// +----------------------------------------------------------------------

namespace Think\Upload\Driver;

class Tietuku
{

    /**
     * 上传文件根目录
     * @var string
     */
    private $rootPath;

    /**
     * 上传错误信息
     * @var string
     */
    private $error  = '';
    private $config = array(
        'secretKey' => '', //贴图库sk
        'accessKey' => '', //贴图库ak
        'aid'       => '', //贴图库相册id
    );

    /**
     * 构造函数，用于设置上传根路径
     * @param array  $config FTP配置
     */
    public function __construct($config)
    {
        $this->config = array_merge($this->config, $config);
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'Tietuku' . DIRECTORY_SEPARATOR . 'tietuku.class.php';
        /* 设置根目录 */
        $this->Client = new \TTKClient($this->config['accessKey'], $this->config['secretKey']);
    }

    /**
     * 检测上传根目录(贴图库上传时支持自动创建目录，直接返回)
     * @param string $rootpath   根目录
     * @return boolean true-检测通过，false-检测失败
     */
    public function checkRootPath($rootpath)
    {
        $this->rootPath = trim($rootpath, './') . '/';
        return true;
    }

    /**
     * 检测上传目录(贴图库上传时支持自动创建目录，直接返回)
     * @param  string $savepath 上传目录
     * @return boolean          检测结果，true-通过，false-失败
     */
    public function checkSavePath($savepath)
    {
        return true;
    }

    /**
     * 创建文件夹 (贴图库上传时支持自动创建目录，直接返回)
     * @param  string $savepath 目录名称
     * @return boolean          true-创建成功，false-创建失败
     */
    public function mkdir($savepath)
    {
        return true;
    }

    /**
     * 保存指定文件
     * @param  array   $file    保存的文件信息
     * @param  boolean $replace 同名文件是否覆盖
     * @return boolean          保存状态，true-成功，false-失败
     */
    public function save(&$file, $replace = true)
    {
        $file['name'] = $file['savepath'] . $file['savename'];
        $result       = $this->Client->uploadFile($this->config['aid'], $file['tmp_name'], $file['name']);
        $result       = json_decode($result, true);
        d_f('upload', $result);
        if (isset($result[0])) {
            $result = $result[0];
        }

        if (isset($result['code'])) {
            $this->error = 'token错误';
        } else {
            $file['url'] = $result['linkurl'];
        }

        return isset($result['width']) ? true : false;
    }

    /**
     * 获取最后一次上传错误信息
     * @return string 错误信息
     */
    public function getError()
    {
        return $this->error;
    }
}

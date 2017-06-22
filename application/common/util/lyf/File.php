<?php
namespace lyf;

/**
 * 文件及文件夹处理类
 */
class File
{
    /**
     * 创建目录
     * @param $dir  目录名
     * @return boolean true 成功， false 失败
     */
    public static function mk_dir($dir)
    {
        $dir = rtrim($dir, '/') . '/';
        if (!is_dir($dir)) {
            if (mkdir($dir, 0700) == false) {
                return false;
            }
            return true;
        }
        return true;
    }

    /**
     * 基于数组创建目录和文件
     * @param array $files 文件名数组
     */
    public static function create_dir_or_files($files)
    {
        foreach ($files as $key => $value) {
            if (substr($value, -1) == '/') {
                mkdir($value);
            } else {
                @file_put_contents($value, '');
            }
        }
    }

    /**
     * 读取文件内容
     * @param $filename  文件名
     * @return string 文件内容
     */
    public static function read_file($filename)
    {
        $content = '';
        if (function_exists('file_get_contents')) {
            @$content = file_get_contents($filename);
        } else {
            if (@$fp = fopen($filename, 'r')) {
                @$content = fread($fp, filesize($filename));
                @fclose($fp);
            }
        }
        return $content;
    }

    /**
     * 写文件
     * @param $filename  文件名
     * @param $writetext 文件内容
     * @param $openmod     打开方式
     * @return boolean true 成功, false 失败
     */
    public static function write_file($filename, $writetext, $openmod = 'w')
    {
        if (@$fp = fopen($filename, $openmod)) {
            flock($fp, 2);
            fwrite($fp, $writetext);
            fclose($fp);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除目录
     * @param $dirName      原目录
     * @return boolean true 成功, false 失败
     */
    public static function del_dir($dirName)
    {
        if (!file_exists($dirName)) {
            return false;
        }

        $dir = opendir($dirName);
        while ($fileName = readdir($dir)) {
            $file = $dirName . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file)) {
                    self::del_dir($file);
                } else {
                    unlink($file);
                }
            }
        }
        closedir($dir);
        return rmdir($dirName);
    }

    /**
     * 复制目录
     * @param $surDir      原目录
     * @param $toDir      目标目录
     * @return boolean true 成功, false 失败
     */
    public static function copy_dir($surDir, $toDir)
    {
        $surDir = rtrim($surDir, '/') . '/';
        $toDir  = rtrim($toDir, '/') . '/';
        if (!file_exists($surDir)) {
            return false;
        }

        if (!file_exists($toDir)) {
            self::mk_dir($toDir);
        }
        $file = opendir($surDir);
        while ($fileName = readdir($file)) {
            $file1 = $surDir . '/' . $fileName;
            $file2 = $toDir . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file1)) {
                    self::copy_dir($file1, $file2);
                } else {
                    copy($file1, $file2);
                }
            }
        }
        closedir($file);
        return true;
    }

    /**
     * 列出目录
     * @param $dir  目录名
     * @return 目录数组。列出文件夹下内容，返回数组 $dirArray['dir']:存文件夹；$dirArray['file']：存文件
     */
    public static function get_dirs($dir)
    {
        $dir          = rtrim($dir, '/') . '/';
        $dirArray[][] = null;
        if (false != ($handle = opendir($dir))) {
            $i = 0;
            $j = 0;
            while (false !== ($file = readdir($handle))) {
                if (is_dir($dir . $file)) {
                    //判断是否文件夹
                    $dirArray['dir'][$i] = $file;
                    $i++;
                } else {
                    $dirArray['file'][$j] = $file;
                    $j++;
                }
            }
            closedir($handle);
        }
        return $dirArray;
    }

    /**
     * 统计文件夹大小
     * @param $dir  目录名
     * @return number 文件夹大小(单位 B)
     */
    public static function get_size($dir)
    {
        $dirlist = opendir($dir);
        $dirsize = 0;
        while (false !== ($folderorfile = readdir($dirlist))) {
            if ($folderorfile != "." && $folderorfile != "..") {
                if (is_dir("$dir/$folderorfile")) {
                    $dirsize += self::get_size("$dir/$folderorfile");
                } else {
                    $dirsize += filesize("$dir/$folderorfile");
                }
            }
        }
        closedir($dirlist);
        return $dirsize;
    }

    /**
     * 检测是否为空文件夹
     * @param $dir  目录名
     * @return boolean true 空， fasle 不为空
     */
    public static function empty_dir($dir)
    {
        return (($files = @scandir($dir)) && count($files) <= 2);
    }

    /**
     * 文件缓存与文件读取
     * @param $name  文件名
     * @param $value  文件内容,为空则获取缓存
     * @param $path   文件所在目录,默认是当前应用的DATA目录
     * @param $cached  是否缓存结果,默认缓存
     * @return 返回缓存内容
     */
    public function cache($name, $value = '', $path = DATA_PATH, $cached = true)
    {
        static $_cache = array();
        $filename      = $path . $name . '.php';
        if ('' !== $value) {
            if (is_null($value)) {
                // 删除缓存
                return false !== strpos($name, '*') ? array_map("unlink", glob($filename)) : unlink($filename);
            } else {
                // 缓存数据
                $dir = dirname($filename);
                // 目录不存在则创建
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }

                $_cache[$name] = $value;
                return file_put_contents($filename, strip_whitespace("<?php\treturn " . var_export($value, true) . ";?>"));
            }
        }
        if (isset($_cache[$name]) && $cached == true) {
            return $_cache[$name];
        }

        // 获取缓存数据
        if (is_file($filename)) {
            $value         = include $filename;
            $_cache[$name] = $value;
        } else {
            $value = false;
        }
        return $value;
    }
}

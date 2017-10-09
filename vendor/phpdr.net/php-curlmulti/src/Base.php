<?php
namespace Ares333\CurlMulti;

/**
 * CurlMulti_Core wrapper, more easy to use
 *
 * @author admin@phpdr.net
 *
 */
class Base
{

    protected $curl;

    function __construct()
    {
        $this->curl = new Core();
        // default fail callback
        $this->curl->cbFail = array(
            $this,
            'cbCurlFail'
        );
        // default info callback
        $this->curl->cbInfo = array(
            $this,
            'cbCurlInfo'
        );
    }

    /**
     *
     * @param string $name
     * @return string relative path
     */
    function hashpath($name)
    {
        $file = md5($name);
        $file = substr($file, 0, 3) . '/' . substr($file, 3, 3) . '/' .
             substr($file, 6);
        return $file;
    }

    /**
     * content between start and end
     *
     * @param string $str
     * @param string $start
     * @param string $end
     * @param String $mode
     *            g greed
     *            ng non-greed
     * @return string boolean
     */
    function substr($str, $start, $end = null, $mode = 'g')
    {
        if (isset($start)) {
            $pos1 = strpos($str, $start);
        } else {
            $pos1 = 0;
        }
        if (isset($end)) {
            if ($mode == 'g') {
                $pos2 = strrpos($str, $end);
            } elseif ($mode == 'ng') {
                $pos2 = strpos($str, $end, $pos1);
            } else {
                user_error('mode is invalid, mode=' . $mode, E_USER_ERROR);
            }
        } else {
            $pos2 = strlen($str);
        }
        if (false === $pos1 || false === $pos2 || $pos2 < $pos1) {
            return false;
        }
        $len = strlen($start);
        return substr($str, $pos1 + $len, $pos2 - $pos1 - $len);
    }

    /**
     * default CurlMulti_Core fail callback
     *
     * @param array $error
     * @param mixed $args
     *            args in CurlMulti_Core::add()
     */
    function cbCurlFail($error, $args)
    {
        $err = $error['error'];
        echo "\nCurl error $err[0]: $err[1], url=" . $error['info']['url'] .
             "\n\n";
    }

    /**
     * default CurlMulti_Core info callback
     *
     * @param array $info
     *            array('all'=>array(),'running'=>array())
     *
     */
    function cbCurlInfo($info)
    {
        static $meta = array(
            'downloadSpeed' => array(
                0,
                'SPD'
            ),
            'downloadSize' => array(
                0,
                'DWN'
            ),
            'finishNum' => array(
                0,
                'FNH'
            ),
            'cacheNum' => array(
                0,
                'CAC'
            ),
            'taskRunningNum' => array(
                0,
                'TKR'
            ),
            'activeNum' => array(
                0,
                'ACT'
            ),
            'taskPoolNum' => array(
                0,
                'TKP'
            ),
            'queueNum' => array(
                0,
                'QUE'
            ),
            'taskNum' => array(
                0,
                'TSK'
            ),
            'failNum' => array(
                0,
                'FAN'
            )
        );
        static $isFirst = true;
        $all = $info['all'];
        $all['downloadSpeed'] = round($all['downloadSpeed'] / 1024) . 'KB';
        $all['downloadSize'] = round($all['downloadSize'] / 1024 / 1024) . "MB";
        // clean
        foreach (array_keys($meta) as $v) {
            if (! array_key_exists($v, $all)) {
                unset($meta[$v]);
            }
        }
        $content = '';
        $lenPad = 2;
        $caption = '';
        foreach (array(
            'meta'
        ) as $name) {
            foreach ($$name as $k => $v) {
                if (! isset($all[$k])) {
                    continue;
                }
                if (mb_strlen($all[$k]) > $v[0]) {
                    $v[0] = mb_strlen($all[$k]);
                }
                if (PHP_OS == 'Linux') {
                    if (mb_strlen($v[1]) > $v[0]) {
                        $v[0] = mb_strlen($v[1]);
                    }
                    $caption .= sprintf('%-' . ($v[0] + $lenPad) . 's', $v[1]);
                    $content .= sprintf('%-' . ($v[0] + $lenPad) . 's',
                        $all[$k]);
                } else {
                    $format = '%-' . ($v[0] + strlen($v[1]) + 1 + $lenPad) . 's';
                    $content .= sprintf($format, $v[1] . ':' . $all[$k]);
                }
                ${$name}[$k] = $v;
            }
        }
        if (PHP_OS == 'Linux') {
            if ($isFirst) {
                echo "\n";
                $isFirst = false;
            }
            $str = "\33[A\r\33[K" . $caption . "\n\r\33[K" . rtrim($content);
        } else {
            $str = "\r" . rtrim($content);
        }
        echo $str;
    }

    /**
     * html encoding transform
     *
     * @param string $html
     * @param string $in
     * @param string $out
     * @param string $content
     * @param string $mode
     *            auto|iconv|mb_convert_encoding
     * @return string
     */
    function encoding($html, $in = null, $out = null, $mode = 'auto')
    {
        $valid = array(
            'auto',
            'iconv',
            'mb_convert_encoding'
        );
        if (! isset($out)) {
            $out = 'UTF-8';
        }
        if (! in_array($mode, $valid)) {
            user_error('invalid mode, mode=' . $mode, E_USER_ERROR);
        }
        $if = function_exists('mb_convert_encoding');
        $if = $if && ($mode == 'auto' || $mode == 'mb_convert_encoding');
        if (function_exists('iconv') && ($mode == 'auto' || $mode == 'iconv')) {
            $func = 'iconv';
        } elseif ($if) {
            $func = 'mb_convert_encoding';
        } else {
            user_error('charsetTrans failed, no function', E_USER_ERROR);
        }
        $pattern = '/(<meta[^>]*?charset=(["\']?))([a-z\d_\-]*)(\2[^>]*?>)/is';
        if (! isset($in)) {
            $n = preg_match($pattern, $html, $in);
            if ($n > 0) {
                $in = $in[3];
            } else {
                if (function_exists('mb_detect_encoding')) {
                    $in = mb_detect_encoding($html);
                } else {
                    $in = null;
                }
            }
        }
        if (isset($in)) {
            $old = error_reporting(error_reporting() & ~ E_NOTICE);
            $html = call_user_func($func, $in, $out . '//IGNORE', $html);
            error_reporting($old);
            $html = preg_replace($pattern, "\\1$out\\4", $html, 1);
        }
        return $html;
    }

    /**
     * is a full url
     *
     * @param string $str
     * @return boolean
     */
    function isUrl($str)
    {
        $str = ltrim($str);
        return in_array(substr($str, 0, 7),
            array(
                'http://',
                'https:/'
            ));
    }

    /**
     * urlCurrent should be redirected final url.Final url normally has '/' suffix.
     *
     * @param string $uri
     *            uri in the html
     * @param string $urlCurrent
     *            redirected final url of the page
     * @return string
     */
    function uri2url($uri, $urlCurrent)
    {
        if (empty($uri)) {
            return $urlCurrent;
        }
        if ($this->isUrl($uri)) {
            return $uri;
        }
        if (! $this->isUrl($urlCurrent)) {
            user_error('url is invalid, url=' . $urlCurrent, E_USER_ERROR);
        }
        // uri started with ?,#
        if (0 === strpos($uri, '#') || 0 === strpos($uri, '?')) {
            if (false !== ($pos = strpos($urlCurrent, '#'))) {
                $urlCurrent = substr($urlCurrent, 0, $pos);
            }
            if (false !== ($pos = strpos($urlCurrent, '?'))) {
                $urlCurrent = substr($urlCurrent, 0, $pos);
            }
            return $urlCurrent . $uri;
        }
        if (0 === strpos($uri, './')) {
            $uri = substr($uri, 2);
        }
        $urlDir = $this->urlDir($urlCurrent);
        if (0 === strpos($uri, '/')) {
            $len = strlen(parse_url($urlDir, PHP_URL_PATH));
            return substr($urlDir, 0, 0 - $len) . $uri;
        } else {
            return $urlDir . $uri;
        }
    }

    /**
     * get relative uri of the current page.
     * urlCurrent should be redirected final url.Final url normally has '/' suffix.
     *
     * @param string $url
     * @param string $urlCurrent
     *            redirected final url of the html page
     * @return string
     */
    function url2uri($url, $urlCurrent)
    {
        if (! $this->isUrl($url)) {
            user_error('url is invalid, url=' . $url, E_USER_ERROR);
        }
        $urlDir = $this->urlDir($urlCurrent);
        $parse1 = parse_url($url);
        $parse2 = parse_url($urlDir);
        if (! array_key_exists('port', $parse1)) {
            $parse1['port'] = null;
        }
        if (! array_key_exists('port', $parse2)) {
            $parse2['port'] = null;
        }
        $eq = true;
        foreach (array(
            'scheme',
            'host',
            'port'
        ) as $v) {
            if (isset($parse1[$v]) && isset($parse2[$v])) {
                if ($parse1[$v] != $parse2[$v]) {
                    $eq = false;
                    break;
                }
            }
        }
        $path = null;
        if ($eq) {
            $len = strlen($urlDir) - strlen(parse_url($urlDir, PHP_URL_PATH));
            $path1 = substr($url, $len + 1);
            $path2 = substr($urlDir, $len + 1);
            $arr1 = $arr2 = array();
            if (! empty($path1)) {
                $arr1 = explode('/', rtrim($path1, '/'));
            }
            if (! empty($path2)) {
                $arr2 = explode('/', rtrim($path2, '/'));
            }
            foreach ($arr1 as $k => $v) {
                if (array_key_exists($k, $arr2) && $v == $arr2[$k]) {
                    unset($arr1[$k], $arr2[$k]);
                } else {
                    break;
                }
            }
            $path = '';
            foreach ($arr2 as $v) {
                $path .= '../';
            }
            $path .= implode('/', $arr1);
        }
        return $path;
    }

    /**
     * url should be redirected final url.Final url normally has '/' suffix.
     *
     * @param string $url
     *            the final directed url
     * @return string
     */
    function urlDir($url)
    {
        if (! $this->isUrl($url)) {
            user_error('url is invalid, url=' . $url, E_USER_ERROR);
        }
        $parse = parse_url($url);
        $urlDir = $url;
        if (isset($parse['path'])) {
            // none / end url should be finally redirected to / ended url
            if ('/' != substr($urlDir, - 1)) {
                $urlDir = dirname($urlDir) . '/';
            }
        }
        return $urlDir;
    }

    /**
     * get CurlMulti\Core instance
     *
     * @return \Ares333\CurlMulti\Core
     */
    function getCurl()
    {
        return $this->curl;
    }
}
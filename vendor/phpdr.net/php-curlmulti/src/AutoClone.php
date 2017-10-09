<?php
namespace Ares333\CurlMulti;

use phpQuery;

/**
 * Website copy, keep original structure
 *
 * @author admin@phpdr.net
 *
 */
class AutoClone extends Base
{

    // local file expire time
    public $expire = null;

    public $downloadPic = true;

    // suffix for href
    public $download = array(
        'zip',
        'rar'
    );

    // init url
    private $url;

    // absolute local dir
    private $dir;

    // processed url
    private $urlAdded = array();

    // windows system flag
    private $isWin;

    /**
     *
     * @param string $url
     *            array( 'http://www.xxx.com/abc' => array( 'def/' => array('depth'=>2) )
     * @param string $dir
     */
    function __construct($url, $dir)
    {
        parent::__construct();
        $this->curl->opt[CURLOPT_HEADER] = false;
        if (! is_dir($dir)) {
            user_error('dir not exists, dir=' . $dir, E_USER_ERROR);
        }
        $this->url = $url;
        $this->dir = $dir;
        $this->isWin = (0 === strpos(PHP_OS, 'WIN'));
    }

    /**
     * start clone
     */
    function start()
    {
        foreach ($this->url as $k => $v) {
            foreach ($v as $k1 => $v1) {
                $url = $k . $k1;
                $this->getCurl()->add(
                    array(
                        'opt' => array(
                            CURLOPT_URL => $url
                        ),
                        'args' => array(
                            'url' => $url,
                            'file' => $this->url2file($url)
                        )
                    ),
                    array(
                        $this,
                        'cbProcess'
                    ));
                $this->urlAdd($url);
            }
        }
        $this->getCurl()->start();
        if (isset($this->getCurl()->cbInfo)) {
            echo "\n";
        }
    }

    /**
     * download and html callback
     *
     * @param array $r
     * @param mixed $args
     *
     */
    function cbProcess($r, $args)
    {
        if (200 == $r['info']['http_code']) {
            $urlDownload = array();
            $urlParse = array();
            if (isset($r['body']) &&
                 0 === strpos($r['info']['content_type'], 'text')) {
                $urlCurrent = $args['url'];
                $pq = phpQuery::newDocumentHTML($r['body']);
                // css
                $list = $pq['link[type$=css]'];
                foreach ($list as $v) {
                    $v = pq($v);
                    $url = $this->uri2url($v->attr('href'), $urlCurrent);
                    $v->attr('href', $this->url2uriClone($url, $urlCurrent));
                    $urlDownload[$url] = array(
                        'type' => 'css'
                    );
                }
                // script
                $script = $pq['script[type$=script]'];
                foreach ($script as $v) {
                    $v = pq($v);
                    if (null != $v->attr('src')) {
                        $url = $this->uri2url($v->attr('src'), $urlCurrent);
                        $v->attr('src', $this->url2uriClone($url, $urlCurrent));
                        $urlDownload[$url] = array();
                    }
                }
                // pic
                $pic = $pq['img,image'];
                if ($this->downloadPic) {
                    foreach ($pic as $v) {
                        $v = pq($v);
                        $url = $this->uri2url($v->attr('src'), $urlCurrent);
                        $v->attr('src', $this->url2uriClone($url, $urlCurrent));
                        $urlDownload[$url] = array();
                    }
                } else {
                    foreach ($pic as $v) {
                        $v = pq($v);
                        $v->attr('src',
                            $this->uri2url($v->attr('src'), $urlCurrent));
                    }
                }
                // link xml
                $list = $pq['link[type$=xml]'];
                foreach ($list as $v) {
                    $v = pq($v);
                    $url = $this->uri2url($v->attr('href'), $urlCurrent);
                    if ($this->isProcess($url)) {
                        $v->attr('href', $this->url2uriClone($url, $urlCurrent));
                        $urlDownload[$url] = array();
                    }
                }
                // href
                $a = $pq['a[href]'];
                foreach ($a as $v) {
                    $v = pq($v);
                    $href = $v->attr('href');
                    if (strtolower(substr(ltrim($href), 0, 11)) == 'javascript:') {
                        continue;
                    }
                    $url = $this->uri2url($href, $urlCurrent);
                    $ext = pathinfo($href, PATHINFO_EXTENSION);
                    if (in_array($ext, $this->download)) {
                        $isProcess = $this->isProcess($url);
                        if ($isProcess) {
                            $urlDownload[$url] = array();
                        }
                    } else {
                        $isProcess = $this->isProcess($url);
                        if ($isProcess) {
                            $urlParse[$url] = array();
                        }
                    }
                    if ($isProcess) {
                        $v->attr('href', $this->url2uriClone($url, $urlCurrent));
                    } else {
                        $v->attr('href', $url);
                    }
                }
                $r['body'] = $pq->html();
                $path = $args['file'];
                if (isset($path)) {
                    if ($this->isWin) {
                        $path = mb_convert_encoding($path, 'gbk', 'utf-8');
                    }
                    file_put_contents($path, $r['body'], LOCK_EX);
                }
                phpQuery::unloadDocuments();
            } elseif ($args['isDownload']) {
                if ('css' == $args['type']) {
                    $content = file_get_contents($args['file']);
                    $uri = array();
                    // import
                    preg_match_all('/@import\s+url\s*\((.+)\);/iU', $content,
                        $matches);
                    if (! empty($matches[1])) {
                        $uri = array_merge($uri, $matches[1]);
                    }
                    // url in css
                    preg_match_all('/:\s*url\((\'|")?(.+?)\\1?\)/i', $content,
                        $matches);
                    if (! empty($matches[2])) {
                        $uri = array_merge($uri, $matches[2]);
                    }
                    foreach ($uri as $v) {
                        $urlDownload[$this->urlDir($r['info']['url']) . $v] = array(
                            'type' => 'css'
                        );
                    }
                }
            }
            // add
            foreach (array(
                'urlDownload',
                'urlParse'
            ) as $v) {
                foreach ($$v as $k1 => $v1) {
                    if (! $this->urlAdd($k1, true)) {
                        $file = $this->url2file($k1);
                        if (null == $file) {
                            continue;
                        }
                        $type = null;
                        if (isset($v1['type'])) {
                            $type = $v1['type'];
                        }
                        $opt = array(
                            CURLOPT_URL => $k1
                        );
                        if ($v === 'urlDownload') {
                            $opt[CURLOPT_FILE] = fopen($file, 'w');
                        }
                        $item = array(
                            'opt' => $opt,
                            'args' => array(
                                'url' => $k1,
                                'file' => $file,
                                'type' => $type,
                                'isDownload' => $v == 'urlDownload'
                            )
                        );
                        $this->getCurl()->add($item,
                            array(
                                $this,
                                'cbProcess'
                            ));
                        $this->urlAdd($k1);
                    }
                }
            }
        } else {
            return array(
                'error' => 'http error ' . $r['info']['http_code'],
                'cache' => array(
                    'enable' => false
                )
            );
        }
    }

    /**
     * is needed to process
     *
     * @param unknown $url
     */
    private function isProcess($url)
    {
        $doProcess = false;
        foreach ($this->url as $k => $v) {
            if (0 === strpos($url, $k) || $url . '/' == $k) {
                if (! empty($v['depth'])) {
                    $temp = $this->urlDepth($url, $k);
                    if (isset($temp) && $temp > $v['depth']) {
                        continue;
                    }
                }
                $doProcess = true;
                break;
            }
        }
        return $doProcess;
    }

    /**
     * calculate relative depth
     *
     * @param string $url
     * @param string $urlBase
     */
    private function urlDepth($url, $urlBase)
    {
        if ($this->isUrl($url) && $this->isUrl($urlBase)) {
            if (0 === strpos($url, $urlBase)) {
                $path = ltrim(substr($url, strlen($urlBase)), '/');
                if (false !== $path) {
                    $depth = 0;
                    if (! empty($path)) {
                        $depth = count(explode('/', $path));
                    }
                    return $depth;
                }
            }
        }
    }

    /**
     * url2uri for this class
     *
     * @param string $url
     * @param string $urlCurrent
     * @return string
     */
    private function url2uriClone($url, $urlCurrent)
    {
        $path = $this->url2uri($url, $urlCurrent);
        $path = $this->fixPath($path);
        if (! isset($path)) {
            $dir2 = $this->urlDir($urlCurrent);
            $path1 = $this->getPath($url);
            $path2 = ltrim(parse_url($dir2, PHP_URL_PATH), '/');
            $arr2 = array();
            if (! empty($path2)) {
                $arr2 = explode('/', rtrim($path2, '/'));
            }
            $path = '../';
            foreach ($arr2 as $v) {
                $path .= '../';
            }
            $path .= $path1;
        }
        return $path;
    }

    /**
     * compute local absolute path
     *
     * @param string $url
     * @return string
     */
    private function url2file($url)
    {
        $file = $this->dir . '/' . $this->getPath($url);
        $dir = dirname($file);
        if ($this->isWin) {
            $dir = mb_convert_encoding($dir, 'gbk', 'utf-8');
        }
        if (! file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        if (file_exists($file)) {
            if (! isset($this->expire) ||
                 time() - filemtime($file) < $this->expire) {
                $file = null;
            }
        }
        return $file;
    }

    /**
     * relative local file path
     *
     * @param string $url
     * @return string
     */
    private function getPath($url)
    {
        $parse = parse_url(trim($url));
        if (! isset($parse['path'])) {
            $parse['path'] = '';
        }
        $parse['path'] = $this->fixPath($parse['path']);
        $port = '';
        if (isset($parse['port'])) {
            $port = '_' . $parse['port'];
        }
        $path = $parse['scheme'] . '_' . $parse['host'] . $port;
        $path .= $parse['path'] . $this->getQuery($url);
        $invalid = array(
            '?',
            '*',
            ':',
            '|',
            '\\',
            '<',
            '>'
        );
        $invalidName = array(
            "con",
            "aux",
            "nul",
            "prn",
            "com0",
            "com1",
            "com2",
            "com3",
            "com4",
            "com5",
            "com6",
            "com7",
            "com8",
            "com9",
            "lpt0",
            "lpt1",
            "lpt2",
            "lpt3",
            "lpt4",
            "lpt5",
            "lpt6",
            "lpt7",
            "lpt8",
            "lpt9"
        );
        $invalidNameReplace = array_map(
            function ($v) {
                return '_' . $v;
            }, $invalidName);
        $path = str_replace($invalid, '-', $path);
        $path = str_replace($invalidName, $invalidNameReplace, $path);
        return $path;
    }

    /**
     * calculate query
     *
     * @param string $url
     * @return string
     */
    private function getQuery($url)
    {
        $query = parse_url($url, PHP_URL_QUERY);
        if (! empty($query)) {
            parse_str($query, $query);
            sort($query);
            $query = http_build_query($query);
            if (strlen($query) >= 250) {
                $query = md5($query);
            }
            $query = '？' . $query;
        }
        return $query;
    }

    /**
     * add processed url or check
     *
     * @param string $url
     * @param bool $check
     */
    private function urlAdd($url, $check = false)
    {
        $md5 = md5($url);
        $level1 = substr($md5, 0, 3);
        $level2 = substr($md5, 3, 3);
        if ($check) {
            $res = ! empty($this->urlAdded[$level1][$level2]);
            $res = $res && in_array($url, $this->urlAdded[$level1][$level2]);
            return $res;
        } else {
            if (! array_key_exists($level1, $this->urlAdded)) {
                $this->urlAdded[$level1] = array(
                    $level2 => array(
                        $url
                    )
                );
            } elseif (! array_key_exists($level2, $this->urlAdded[$level1])) {
                $this->urlAdded[$level1][$level2] = array(
                    $url
                );
            } elseif (! in_array($url, $this->urlAdded[$level1][$level2])) {
                $this->urlAdded[$level1][$level2][] = $url;
            }
        }
    }

    /**
     * fix uri and file path
     *
     * @param string $path
     * @return string
     */
    private function fixPath($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if (empty($ext)) {
            if (substr($path, - 1) === '/') {
                $path .= 'index.html';
            } else {
                $path .= '.html';
            }
        }
        return $path;
    }
}
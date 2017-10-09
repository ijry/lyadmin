<?php
require_once '../vendor/autoload.php';
use Ares333\CurlMulti\Core;
use Ares333\CurlMulti\Base;
$curl = new Core();
$curl->cbInfo = array(
    new Base(),
    'cbCurlInfo'
);
$curl->maxThread = 3;
$url = 'http://www.baidu.com';
for ($i = 0; $i < 100; $i ++) {
    $curl->add(
        array(
            'opt' => array(
                CURLOPT_URL => $url . '?wd=' . $i
            )
        ));
}
$curl->start();
<?php
require_once '../vendor/autoload.php';
use Ares333\CurlMulti\Core;
$url = array(
    'http://baidu.com',
    'http://bing.com'
);
$curl = new Core();
foreach ($url as $v) {
    $curl->add(
        array(
            'opt' => array(
                CURLOPT_URL => $v
            ),
            'args' => array(
                'test' => 'this is user arg for ' . $v
            )
        ), 'cbProcess');
}
// start spider
$curl->start();

function cbProcess($r, $args)
{
    echo "success, url=" . $r['info']['url'] . "\n";
    print_r(array_keys($r));
    print_r($args);
}
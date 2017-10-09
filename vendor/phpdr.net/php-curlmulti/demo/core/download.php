<?php
require_once '../vendor/autoload.php';
use Ares333\CurlMulti\Core;
$curl = new Core();
$url = 'http://www.baidu.com/img/bd_logo1.png';
$file = __DIR__ . '/baidu.png';
// $fp will auto closed by curl after cbProcess
$fp = fopen($file, 'w');
$curl->add(
    array(
        'opt' => array(
            CURLOPT_URL => $url,
            CURLOPT_FILE => $fp,
            CURLOPT_HEADER => false
        ),
        'args' => array(
            'file' => $file
        )
    ), 'cbProcess')->start();

function cbProcess($r, $args)
{
    echo "download finished successfully, file=$args[file]\n";
}
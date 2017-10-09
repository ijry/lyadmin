<?php
require_once '../vendor/autoload.php';
use Ares333\CurlMulti\Core;
$curl = new Core();
// global opt
$curl->opt[CURLOPT_RETURNTRANSFER] = false;
$url = 'http://www.baidu.com';
$curl->add(
    array(
        // this will override $curl->opt[CURLOPT_RETURNTRANSFER]
        'opt' => array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        )
    ), 
    function ($r, $args) {
        echo "content length: " . strlen($r['body']);
    });
$curl->start();
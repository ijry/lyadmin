<?php
// execute in order of task added
require_once '../vendor/autoload.php';
use Ares333\CurlMulti\Core;
$curl = new Core();
$curl->maxThread = 1;
// queue or stack
$curl->taskPoolType = 'stack';
$url = 'http://www.baidu.com';
for ($i = 0; $i < 10; $i ++) {
    $curl->add(
        array(
            'opt' => array(
                CURLOPT_URL => $url . '?wd=' . $i
            ),
            'args' => array(
                'i' => $i
            )
        ), 'cbProcess');
    echo "$i added\n";
}
$curl->start();

function cbProcess($r, $args)
{
    echo $args['i'] . " finished\n";
}
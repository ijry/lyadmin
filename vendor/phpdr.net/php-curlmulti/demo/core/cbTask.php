<?php
require_once '../vendor/autoload.php';
use Ares333\CurlMulti\Core;
$curl = new Core();
$curl->maxThread = 3;
$curl->cbTask = 'cbTask';
$curl->start();

function cbTask()
{
    static $i = 0, $j = 0;
    global $curl;
    $count = 10;
    if ($i < $count) {
        $curl->add(
            array(
                'opt' => array(
                    CURLOPT_URL => 'http://www.baidu.com?wd=' . $i
                )
            ));
        $i ++;
        if ($i == $count) {
            $curl->cbTask = null;
        }
    }
    echo $i . ' tasks added, cbTask called ' . ++ $j . " times\n";
}
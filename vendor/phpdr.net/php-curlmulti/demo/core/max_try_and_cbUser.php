<?php
require_once '../vendor/autoload.php';
use Ares333\CurlMulti\Core;
$url = 'http://8.8.8.8';
$curl = new Core();
// timeout will occur 10 times
$curl->maxTry = 10;
$curl->opt[CURLOPT_CONNECTTIMEOUT] = 1;
$curl->opt[CURLOPT_TIMEOUT] = 1;
$curl->cbFail = 'cbFail';
$curl->add(array(
    'opt' => array(
        CURLOPT_URL => $url
    )
));
$curl->cbUser = function () {
    static $times = 10;
    static $lastTime = null;
    $time = time();
    if (! isset($lastTime)) {
        $lastTime = $time;
    }
    if ($time > $lastTime) {
        $times -= ($time - $lastTime);
        echo $times . "\n";
        $lastTime = $time;
    }
};
// start spider
$curl->start();

function cbFail($err, $args)
{
    echo "tried 10 times and cost 10 seconds\n";
    print_r($err['error']);
}
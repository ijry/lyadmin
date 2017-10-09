#!/usr/bin/env php
<?php

set_error_handler(function ($severity, $message, $file, $line) {
    if ($severity & error_reporting()) {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }
});

Phar::mapPhar('jane.phar');

require_once 'phar://jane.phar/vendor/autoload.php';

$application = new \Joli\Jane\Application();
$application->run();

__HALT_COMPILER();

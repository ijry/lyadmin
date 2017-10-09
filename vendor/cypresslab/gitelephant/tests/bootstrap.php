<?php

/**
 * This file is part of the GitElephant package.
 *
 * (c) Matteo Giachino <matteog@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Just for fun...
 */

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/GitElephant/TestCase.php';

spl_autoload_register(function ($class) {
    $file = __DIR__.'/../src/'.strtr($class, '\\', '/').'.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }

    $file = __DIR__.'/../vendor/'.strtr($class, '\\', '/').'.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }
});

echo exec('git --version')."\n";

date_default_timezone_set('UTC');

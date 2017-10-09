<?php

namespace Docker\Tests;

use Docker\Docker;
use PHPUnit_Framework_TestCase;

class TestCase extends PHPUnit_Framework_TestCase
{
    private static $docker;

    public static function getDocker()
    {
        if (null === self::$docker) {
            self::$docker = new Docker();
        }

        return self::$docker;
    }
}

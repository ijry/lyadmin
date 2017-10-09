<?php

use PHPGit\Git;

require_once __DIR__ . '/../BaseTestCase.php';

class ConfigCommandTest extends BaseTestCase
{

    public function testConfigSetAndList()
    {
        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $before = $git->config();

        $git->config->set('user.name', 'John Doe');

        $config = $git->config();
        $this->assertArrayHasKey('user.name', $config);

        $expected = 'John Doe';

        if (isset($before['user.name'])) {
            $expected = $before['user.name'] . "\n" . $expected;
        }

        $this->assertEquals($expected, $config['user.name']);
    }

    public function testConfigAdd()
    {
        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $before = $git->config();

        $git->config->set('user.name', 'John Doe');
        $git->config->add('user.name', 'Foo');

        $config = $git->config();
        $this->assertArrayHasKey('user.name', $config);

        $expected = "John Doe\nFoo";

        if (isset($before['user.name'])) {
            $expected = $before['user.name'] . "\n" . $expected;
        }

        $this->assertEquals($expected, $config['user.name']);
    }

} 
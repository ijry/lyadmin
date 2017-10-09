<?php

use PHPGit\Git;

require_once __DIR__ . '/../BaseTestCase.php';

class PullCommandTest extends BaseTestCase
{

    public function testPull()
    {
        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
        $git->pull('origin', 'master');

        $this->assertFileExists($this->directory . '/README.md');
    }

} 
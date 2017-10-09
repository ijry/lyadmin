<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';

class MvCommandTest extends BaseTestCase
{

    public function testMv()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/test.txt', 'foo');
        $git->add('test.txt');
        $git->commit('Initial commit');
        $git->mv('test.txt', 'test2.txt');

        $this->assertFileExists($this->directory . '/test2.txt');
    }

} 
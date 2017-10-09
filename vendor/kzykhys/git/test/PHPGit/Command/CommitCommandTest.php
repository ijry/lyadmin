<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';

class CommitCommandTest extends BaseTestCase
{

    public function testCommit()
    {
        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/test.txt', '');
        $git->add('test.txt');
        $git->commit('Initial commit');
        $logs = $git->log('test.txt');

        $this->assertCount(1, $logs);
    }

} 
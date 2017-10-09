<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';

class PushCommandTest extends BaseTestCase
{

    public function testPush()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory, array('shared' => true, 'bare' => true));

        $git->clone('file://' . realpath($this->directory), $this->directory.'2');
        $git->setRepository($this->directory.'2');

        $filesystem->dumpFile($this->directory.'2/test.txt', 'foobar');
        $git->add('test.txt');
        $git->commit('test');
        $git->push('origin', 'master');

        $git->clone('file://' . realpath($this->directory), $this->directory.'3');

        $this->assertFileExists($this->directory.'3/test.txt');

        $filesystem->remove($this->directory.'2');
        $filesystem->remove($this->directory.'3');
    }

} 
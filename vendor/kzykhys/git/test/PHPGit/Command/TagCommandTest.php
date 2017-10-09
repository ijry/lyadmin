<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';

class TagCommandTest extends BaseTestCase
{

    public function testTagDelete()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'hello');
        $git->add('.');
        $git->commit('Initial commit');
        $git->tag->create('v1.0.0');
        $git->tag->delete('v1.0.0');
        $this->assertCount(0, $git->tag());
    }

    /**
     * @expectedException \PHPGit\Exception\GitException
     */
    public function testTagVerify()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'hello');
        $git->add('.');
        $git->commit('Initial commit');
        $git->tag->create('v1.0.0');
        $git->tag->verify('v1.0.0');
    }

    public function testCreateTagFromCommit()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'hello');
        $git->add('.');
        $git->commit('Initial commit');
        $log = $git->log(null, null, array('limit' => 1));
        $git->tag->create('v1.0.0', $log[0]['hash']);
        $this->assertCount(1, $git->tag());
    }

} 
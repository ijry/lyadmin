<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';

class RebaseCommandTest extends BaseTestCase
{

    public function testRebase()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem->dumpFile($this->directory . '/test.txt', '123');
        $git->add('test.txt');
        $git->commit('initial commit');

        $git->checkout->create('next');
        $filesystem->dumpFile($this->directory . '/test2.txt', '123');
        $git->add('test2.txt');
        $git->commit('test');

        $git->checkout('master');
        $git->rebase('next', 'master');

        $this->assertFileExists($this->directory. '/test2.txt');
    }

    public function testRebaseOnto()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/test.txt', '123');
        $git->add('test.txt');
        $git->commit('initial commit');

        $git->checkout->create('next');
        $filesystem->dumpFile($this->directory . '/test2.txt', '123');
        $git->add('test2.txt');
        $git->commit('test');

        $git->checkout->create('topic', 'next');
        $filesystem->dumpFile($this->directory . '/test3.txt', '123');
        $git->add('test3.txt');
        $git->commit('test');

        $git->rebase('next', null, array('onto' => 'master'));
        $this->assertFileNotExists($this->directory . '/test2.txt');
    }

    public function testRebaseContinue()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem->dumpFile($this->directory . '/test.txt', 'foo');
        $git->add('test.txt');
        $git->commit('initial commit');

        $git->checkout->create('next');
        $filesystem->dumpFile($this->directory . '/test.txt', 'bar');
        $git->add('test.txt');
        $git->commit('next commit');

        $git->checkout('master');
        $filesystem->dumpFile($this->directory . '/test.txt', 'baz');
        $git->add('test.txt');
        $git->commit('master commit');

        try {
            $git->rebase('next');
            $this->fail('GitException should be thrown');
        } catch (\PHPGit\Exception\GitException $e) {
        }

        $filesystem->dumpFile($this->directory . '/test.txt', 'foobar');
        $git->add('test.txt');
        $git->rebase->continues();
    }

    public function testRebaseAbort()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem->dumpFile($this->directory . '/test.txt', 'foo');
        $git->add('test.txt');
        $git->commit('initial commit');

        $git->checkout->create('next');
        $filesystem->dumpFile($this->directory . '/test.txt', 'bar');
        $git->add('test.txt');
        $git->commit('next commit');

        $git->checkout('master');
        $filesystem->dumpFile($this->directory . '/test.txt', 'baz');
        $git->add('test.txt');
        $git->commit('master commit');

        try {
            $git->rebase('next');
            $this->fail('GitException should be thrown');
        } catch (\PHPGit\Exception\GitException $e) {
        }

        $git->rebase->abort();
    }

    public function testRebaseSkip()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem->dumpFile($this->directory . '/test.txt', 'foo');
        $git->add('test.txt');
        $git->commit('initial commit');

        $git->checkout->create('next');
        $filesystem->dumpFile($this->directory . '/test.txt', 'bar');
        $git->add('test.txt');
        $git->commit('next commit');

        $git->checkout('master');
        $filesystem->dumpFile($this->directory . '/test.txt', 'baz');
        $git->add('test.txt');
        $git->commit('master commit');

        try {
            $git->rebase('next');
            $this->fail('GitException should be thrown');
        } catch (\PHPGit\Exception\GitException $e) {
        }

        $git->rebase->skip();
    }

} 
<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';

class MergeCommandTest extends BaseTestCase
{

    public function testMerge()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem->dumpFile($this->directory . '/test.txt', 'foo');
        $git->add('test.txt');
        $git->commit('master');

        $git->checkout->create('develop');
        $filesystem->dumpFile($this->directory . '/test.txt', 'bar');
        $git->add('test.txt');
        $git->commit('develop');

        $git->checkout('master');

        $this->assertEquals('foo', file_get_contents($this->directory . '/test.txt'));

        $git->merge('develop');

        $this->assertEquals('bar', file_get_contents($this->directory . '/test.txt'));
    }

    /**
     * @expectedException \PHPGit\Exception\GitException
     */
    public function testMergeFail()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        // branch:master
        $filesystem->dumpFile($this->directory . '/test.txt', 'foo');
        $git->add('test.txt');
        $git->commit('master');

        // branch:develop
        $git->checkout->create('develop');
        $filesystem->dumpFile($this->directory . '/test.txt', 'bar');
        $git->add('test.txt');
        $git->commit('develop');

        // branch:master
        $git->checkout('master');
        $filesystem->dumpFile($this->directory . '/test.txt', 'baz');
        $git->merge('develop');
    }

    public function testMergeAbort()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        // branch:master
        $filesystem->dumpFile($this->directory . '/test.txt', 'foo');
        $git->add('test.txt');
        $git->commit('master');

        // branch:develop
        $git->checkout->create('develop');
        $filesystem->dumpFile($this->directory . '/test.txt', 'bar');
        $git->add('test.txt');
        $git->commit('develop');

        // branch:master
        $git->checkout('master');
        $filesystem->dumpFile($this->directory . '/test.txt', 'baz');
        $git->add('test.txt');
        $git->commit('master');

        try {
            $git->merge('develop');
            $this->fail('$git->merge("develop") should fail');
        } catch (Exception $e) {
        }

        $git->merge->abort();

        $this->assertEquals('baz', file_get_contents($this->directory . '/test.txt'));
    }

} 
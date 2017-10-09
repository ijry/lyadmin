<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';

class StashCommandTest extends BaseTestCase
{

    public function testStash()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'hello');
        $git->add('.');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hi!');
        $git->stash();

        $this->assertEquals('hello', file_get_contents($this->directory.'/README.md'));
    }

    public function testStashSave()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'hello');
        $git->add('.');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hi!');
        $git->stash->save('stash test');

        $this->assertEquals('hello', file_get_contents($this->directory.'/README.md'));
    }

    public function testStashList()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'hello');
        $git->add('.');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hi!');
        $git->stash();

        $stashes = $git->stash->lists();

        $this->assertCount(1, $stashes);
        $this->assertEquals('master', $stashes[0]['branch']);
        $this->assertStringEndsWith('Initial commit', $stashes[0]['message']);
    }

    public function testStashShow()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'hello');
        $git->add('.');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hi!');
        $git->stash();
        $git->stash->show('stash@{0}');
    }

    public function testStashDrop()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'hello');
        $git->add('.');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hi!');
        $git->stash();
        $git->stash->drop();

        $filesystem->dumpFile($this->directory . '/README.md', 'hi!');
        $git->stash();
        $git->stash->drop('stash@{0}');

        $this->assertCount(0, $git->stash->lists());
    }

    public function testStashPop()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'hello');
        $git->add('.');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hi!');
        $git->stash->save('stash#1');

        $filesystem->dumpFile($this->directory . '/README.md', 'bar');
        $git->stash->save('stash#2');
        $git->stash->pop('stash@{1}');

        $this->assertEquals('hi!', file_get_contents($this->directory.'/README.md'));
        $this->assertCount(1, $git->stash->lists());
    }

    public function testStashApply()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'hello');
        $git->add('.');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hi!');
        $git->stash->save('stash#1');

        $filesystem->dumpFile($this->directory . '/README.md', 'bar');
        $git->stash->save('stash#2');
        $git->stash->apply('stash@{1}');

        $this->assertEquals('hi!', file_get_contents($this->directory.'/README.md'));
        $this->assertCount(2, $git->stash->lists());
    }

    public function testStashBranch()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'hello');
        $git->add('.');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hi!');
        $git->stash();

        $git->stash->branch('dev', 'stash@{0}');
        $status = $git->status();

        $this->assertEquals('dev', $status['branch']);
        $this->assertEquals('hi!', file_get_contents($this->directory.'/README.md'));
    }

    public function testStashClear()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'hello');
        $git->add('.');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hi!');
        $git->stash();
        $git->stash->clear();

        $this->assertCount(0, $git->stash->lists());
    }

    public function testStashCreate()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'hello');
        $git->add('.');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hi!');
        $object = $git->stash->create();

        $this->assertNotEmpty($object);
    }

} 
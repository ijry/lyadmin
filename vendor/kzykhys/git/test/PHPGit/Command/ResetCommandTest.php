<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';


class ResetCommandTest extends BaseTestCase
{

    public function testReset()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'foo');
        $git->add('README.md');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hello');
        $git->add('README.md');

        $git->reset('README.md', 'HEAD');
    }

    public function testResetSoft()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'foo');
        $git->add('README.md');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hello');

        $git->reset->soft();
    }

    public function testResetMixed()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'foo');
        $git->add('README.md');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hello');

        $git->reset->mixed();
    }

    public function testResetHard()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'foo');
        $git->add('README.md');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hello');

        $git->reset->hard('HEAD');
    }

    public function testResetMerge()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'foo');
        $git->add('README.md');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hello');

        $git->reset->merge();
    }

    public function testResetKeep()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'foo');
        $git->add('README.md');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hello');

        $git->reset->keep();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testResetInvalidMode()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $filesystem->dumpFile($this->directory . '/README.md', 'foo');
        $git->add('README.md');
        $git->commit('Initial commit');

        $filesystem->dumpFile($this->directory . '/README.md', 'hello');

        $git->reset->mode('foo');
    }

} 
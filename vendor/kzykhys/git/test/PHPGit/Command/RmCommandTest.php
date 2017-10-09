<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';

class RmCommandTest extends BaseTestCase
{

    public function testRm()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem->dumpFile($this->directory . '/README.md', 'foo');
        $filesystem->dumpFile($this->directory . '/bin/test.php', 'foo');
        $git->add(array('README.md', 'bin/test.php'));
        $git->commit('Initial commit');

        $git->rm('README.md');
        $git->rm('bin', array('recursive' => true));

        $this->assertFileNotExists($this->directory . '/README.md');
    }

    public function testRmCached()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem->dumpFile($this->directory . '/README.md', 'foo');
        $git->add('README.md');
        $git->commit('Initial commit');

        $git->rm->cached('README.md');
        $git->commit('Delete README.md');

        $this->assertFileExists($this->directory . '/README.md');

        $tree = $git->tree();
        $this->assertEquals(array(), $tree);
    }

} 
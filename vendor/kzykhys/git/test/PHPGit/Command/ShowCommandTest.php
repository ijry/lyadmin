<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';

class ShowCommandTest extends BaseTestCase
{

    public function testShow()
    {
        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem->dumpFile($this->directory . '/README.md', 'foobar');
        $git->add('README.md');
        $git->commit('Initial commit');

        $git->show('master', array('format' => 'oneline'));
    }

} 
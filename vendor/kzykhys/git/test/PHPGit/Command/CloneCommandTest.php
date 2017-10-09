<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';

class CloneCommandTest extends BaseTestCase
{

    public function testClone()
    {
        $git = new Git();
        $git->clone('https://github.com/kzykhys/Text.git', $this->directory);
        $git->setRepository($this->directory);

        $this->assertFileExists($this->directory . '/.git');

        $filesystem = new Filesystem();
        $filesystem->remove($this->directory);

        $git->setRepository('.');
        $git->clone('https://github.com/kzykhys/Text.git', $this->directory, array('shared' => true));

        $this->assertFileExists($this->directory . '/.git');
    }

} 
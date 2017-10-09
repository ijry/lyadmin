<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class ArchiveCommandTest extends BaseTestCase
{

    public function testArchive()
    {
        $filesystem = new Filesystem();
        $filesystem->mkdir($this->directory);

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem->dumpFile($this->directory . '/test.txt', 'hello');
        $git->add('test.txt');
        $git->commit('Initial commit');

        $git->archive($this->directory . '/test.zip', 'master', null, array('format' => 'zip', 'prefix' => 'test/'));

        $this->assertFileExists($this->directory . '/test.zip');
    }

} 
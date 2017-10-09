<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';

class CatCommandTest extends BaseTestCase
{

    public function testCatBlob()
    {
        $filesystem = new Filesystem();
        $filesystem->mkdir($this->directory);

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem->dumpFile($this->directory . '/test.txt', 'foo');
        $git->add('test.txt');
        $git->commit('Initial commit');

        $tree = $git->tree();

        $this->assertEquals('foo', $git->cat->blob($tree[0]['hash']));
    }

    public function testCatType()
    {
        $filesystem = new Filesystem();
        $filesystem->mkdir($this->directory);

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem->dumpFile($this->directory . '/test.txt', 'foo');
        $git->add('test.txt');
        $git->commit('Initial commit');

        $tree = $git->tree();

        $this->assertEquals('blob', $git->cat->type($tree[0]['hash']));
    }

    public function testCatSize()
    {
        $filesystem = new Filesystem();
        $filesystem->mkdir($this->directory);

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem->dumpFile($this->directory . '/test.txt', 'foo');
        $git->add('test.txt');
        $git->commit('Initial commit');

        $tree = $git->tree();

        $this->assertEquals(3, $git->cat->size($tree[0]['hash']));
    }

} 
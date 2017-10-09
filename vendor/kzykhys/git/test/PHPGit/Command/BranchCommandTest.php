<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';

class BranchCommandTest extends BaseTestCase
{

    public function setUp()
    {
        parent::setUp();

        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem->dumpFile($this->directory . '/test.txt', '');
        $git->add('test.txt');
        $git->commit('Initial commit');
    }

    public function testBranch()
    {
        $git = new Git();
        $git->setRepository($this->directory);

        $branches = $git->branch();

        $this->assertCount(1, $branches);
        $this->assertEquals('master', $branches['master']['name']);
        $this->assertTrue($branches['master']['current']);
        $this->assertEquals('Initial commit', $branches['master']['title']);
    }

    public function testAllBranch()
    {
        $git = new Git();
        $git->clone('file://' . realpath($this->directory), $this->directory.'2');
        $git->setRepository($this->directory.'2');

        $branches = $git->branch(array('remotes' => true));
        $this->assertArrayHasKey('origin/master', $branches);

        $branches = $git->branch(array('all' => true));
        $this->assertArrayHasKey('master', $branches);
        $this->assertArrayHasKey('remotes/origin/master', $branches);

        $filesystem = new Filesystem();
        $filesystem->remove($this->directory.'2');
    }

    public function testBranchCreate()
    {
        $git = new Git();
        $git->setRepository($this->directory);

        $git->branch->create('1.0');
        $branches = $git->branch();
        $this->assertCount(2, $branches);

        $git->branch->create('1.0-fix', '1.0', array('force' => true));
        $branches = $git->branch();
        $this->assertCount(3, $branches);
        $this->assertArrayHasKey('1.0', $branches);
        $this->assertArrayHasKey('1.0-fix', $branches);
    }

    public function testBranchMove()
    {
        $git = new Git();
        $git->setRepository($this->directory);
        $git->branch->create('1.0');
        $git->branch->move('1.0', '1.0.x');
        $branches = $git->branch();
        $this->assertCount(2, $branches);
        $this->assertArrayHasKey('1.0.x', $branches);

        $git->branch->move('1.0.x', '2.x', array('force' => true));
        $branches = $git->branch();
        $this->assertCount(2, $branches);
        $this->assertArrayHasKey('2.x', $branches);
    }

    public function testBranchDelete()
    {
        $git = new Git();
        $git->setRepository($this->directory);
        $git->branch->create('1.0');
        $git->branch->create('2.0');
        $branches = $git->branch();
        $this->assertCount(3, $branches);

        $git->branch->delete('1.0');
        $branches = $git->branch();
        $this->assertCount(2, $branches);

        $git->branch->delete('2.0', array('force' => true));
        $branches = $git->branch();
        $this->assertCount(1, $branches);
    }

} 
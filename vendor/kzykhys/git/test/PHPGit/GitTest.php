<?php

use PHPGit\Git;

class GitTest extends PHPUnit_Framework_TestCase
{

    public function testGetVersion()
    {
        $git = new Git();
        $this->assertNotEmpty($git->getVersion());
    }

    /**
     * @expectedException \PHPGit\Exception\GitException
     */
    public function testInvalidGitBinary()
    {
        $git = new Git();
        $git->setBin('/foo/bar');
        $git->getVersion();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testBadMethodCall()
    {
        $git = new Git();
        $git->foo();
    }

} 
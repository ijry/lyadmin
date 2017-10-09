<?php
/**
 * @author Matteo Giachino <matteog@gmail.com>
 */

namespace GitElephant\Objects;

use \GitElephant\Objects\Object;
use \GitElephant\TestCase;

class ObjectTest extends TestCase
{
    public function setUp()
    {
        $this->initRepository();
        $this->getRepository()->init();
        $this->addFile('test-file');
        $this->getRepository()->commit('first commit', true);
    }

    public function testGetLastCommitFromTree()
    {
        $tree = $this->getRepository()->getTree('master');
        $testFile = $tree[0];
        $this->assertInstanceOf('GitElephant\Objects\Commit', $testFile->getLastCommit());
        $this->assertEquals('first commit', $testFile->getLastCommit()->getMessage());
    }

    public function testGetLastCommitFromBranch()
    {
        $this->getRepository()->createBranch('test');
        $this->getRepository()->checkout('test');
        $this->addFile('test-in-test-branch');
        $this->getRepository()->commit('test branch commit', true);
        $tree = $this->getRepository()->getTree('test', 'test-in-test-branch');
        $testFile = $tree->getBlob();
        $this->assertInstanceOf('GitElephant\Objects\Commit', $testFile->getLastCommit());
        $this->assertEquals('test branch commit', $testFile->getLastCommit()->getMessage()->getFullMessage());
    }

    public function testGetLastCommitFromTag()
    {
        $this->getRepository()->createTag('test-tag');
        $tag = $this->getRepository()->getTag('test-tag');
        $this->assertInstanceOf('GitElephant\Objects\Commit', $tag->getLastCommit());
        $this->assertEquals('first commit', $tag->getLastCommit()->getMessage());
        $this->addFile('file2');
        $this->getRepository()->commit('tag 2 commit', true);
        $this->getRepository()->createTag('test-tag-2');
        $tag = $this->getRepository()->getTag('test-tag-2');
        $this->assertInstanceOf('GitElephant\Objects\Commit', $tag->getLastCommit());
        $this->assertEquals('tag 2 commit', $tag->getLastCommit()->getMessage());
    }

    public function testRevParse()
    {
        $master = $this->getRepository()->getBranch('master');

        $revParse = $master->revParse();
        $this->assertEquals($master->getSha(), $revParse[0]);
    }

    /**
     * test repository getter and setter
     *
     * @covers GitElephant\Objects\Object::getRepository
     * @covers GitElephant\Objects\Object::setRepository
     */
    public function testGetSetRepository()
    {
        $this->initRepository('object1', 1);
        $repo1 = $this->getRepository(1);

        $this->initRepository('object2', 2);
        $repo2 = $this->getRepository(2);

        $object = new Object($repo1, 'permissions', 'type', 'sha', 'size', 'name', 'path'); // dummy params
        $this->assertSame($repo1, $object->getRepository());
        $object->setRepository($repo2);
        $this->assertSame($repo2, $object->getRepository());
    }
}

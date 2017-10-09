<?php
/**
 * User: matteo
 * Date: 28/10/12
 * Time: 16.16
 *
 * Just for fun...
 */

namespace GitElephant\Objects;

use \GitElephant\TestCase;
use \GitElephant\Objects\Tag;

class TagTest extends TestCase
{
    /**
     * testTag
     */
    public function testTag()
    {
        $this->getRepository()->init();
        $this->addFile('foo');
        $this->getRepository()->commit('commit1', true);
        $this->getRepository()->createTag('test-tag');
        $tag = new Tag($this->getRepository(), 'test-tag');
        $this->assertInstanceOf('GitElephant\Objects\Tag', $tag);
        $this->assertEquals('test-tag', $tag->getName());
        $this->assertEquals('refs/tags/test-tag', $tag->getFullRef());
        $this->assertEquals($this->getRepository()->getCommit()->getSha(), $tag->getSha());
    }

    /**
     * testTagFromStartPoint
     */
    public function testTagFromStartPoint()
    {
        $this->getRepository()->init();
        $this->addFile('foo');
        $this->repository->commit('commit1', true);
        Tag::create($this->repository, 'tag1', $this->repository->getCommit());
        $tag = new Tag($this->repository, 'tag1');
        $this->assertInstanceOf('GitElephant\Objects\Tag', $tag);
        $this->assertEquals($tag->getSha(), $this->repository->getCommit()->getSha());
        $branch = Branch::create($this->repository, 'test-branch');
        Tag::create($this->repository, 'tag2', $branch);
        $tag = new Tag($this->repository, 'tag2');
        $this->assertEquals($tag->getSha(), $branch->getSha());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNonExistentTag()
    {
        $this->getRepository()->init();
        $this->addFile('foo');
        $this->getRepository()->commit('commit1', true);
        $this->getRepository()->createTag('test-tag');
        $tag = new Tag($this->getRepository(), 'test-tag-non-existent');
    }

    /**
     * testCreate
     */
    public function testCreate()
    {
        $this->getRepository()->init();
        $this->addFile('test');
        $this->repository->commit('test', true);
        $this->assertCount(0, $this->repository->getTags());
        Tag::create($this->repository, 'test-tag');
        $this->assertCount(1, $this->repository->getTags());
        Tag::create($this->repository, 'test-tag2', 'test-tag');
        $this->assertCount(2, $this->repository->getTags());
    }
}

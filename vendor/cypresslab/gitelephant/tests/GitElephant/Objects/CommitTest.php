<?php

/**
 * This file is part of the GitElephant package.
 *
 * (c) Matteo Giachino <matteog@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Just for fun...
 */

namespace GitElephant\Objects;

use \GitElephant\TestCase;
use \GitElephant\Objects\Commit;
use \GitElephant\Command\ShowCommand;
use \GitElephant\Command\MainCommand;
use \GitElephant\Command\CommandContainer;

/**
 * CommitTest
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class CommitTest extends TestCase
{
    /**
     * @var \GitElephant\Objects\Commit;
     */
    private $commit;

    public function setUp()
    {
        $this->initRepository();
        $mainCommand = new MainCommand();
        $this->getCaller()->execute($mainCommand->init());
        $this->addFile('foo');
        $this->getCaller()->execute($mainCommand->add());
        $this->getCaller()->execute($mainCommand->commit('first commit'));
    }

    /**
     * commit tests
     */
    public function testCommit()
    {
        $showCommand = new ShowCommand();
        $this->commit = Commit::pick($this->getRepository());
        $this->assertInstanceOf('\GitElephant\Objects\Commit', $this->commit);
        $this->assertInstanceOf('\GitElephant\Objects\Author', $this->commit->getAuthor());
        $this->assertInstanceOf('\GitElephant\Objects\Author', $this->commit->getCommitter());
        $this->assertInstanceOf('\Datetime', $this->commit->getDatetimeAuthor());
        $this->assertInstanceOf('\Datetime', $this->commit->getDatetimeCommitter());
        $this->assertInstanceOf('\GitElephant\Objects\Commit\Message', $this->commit->getMessage());
        $this->assertEquals('first commit', $this->commit->getMessage()->toString());
        $this->assertRegExp('/^\w{40}$/', $this->commit->getSha());
        $this->assertEquals(array(), $this->commit->getParents());
        $this->addFile('foo2');
        $mainCommand = new MainCommand();
        $this->getCaller()->execute($mainCommand->add());
        $this->getCaller()->execute($mainCommand->commit('second commit'));
        $this->getCaller()->execute($showCommand->showCommit('HEAD'));
        $this->commit = Commit::pick($this->getRepository());
        $parents = $this->commit->getParents();
        $this->assertRegExp('/^\w{40}$/', $parents[0]);
    }

    /**
     * constructor regex test
     */
    public function testCommitRegEx()
    {
        $outputLines = array(
            "commit c277373174aa442af12a8e59de1812f3472c15f5",
            "tree 9d36a2d3c5d5bce9c6779a574ed2ba3d274d8016",
            "author matt <matteog@gmail.com> 1326214449 +0100",
            "committer matt <matteog@gmail.com> 1326214449 +0100",
            "",
            "    first commit"
        );

        $mockRepo = $this->getMockRepository();
        $this->addOutputToMockRepo($mockRepo, $outputLines);

        $commit = Commit::pick($mockRepo);
        $committer = $commit->getCommitter();
        $author = $commit->getAuthor();
        $this->assertEquals('matt', $committer->getName());
        $this->assertEquals('matt', $author->getName());

        $outputLines = array(
            "commit c277373174aa442af12a8e59de1812f3472c15f5",
            "tree 9d36a2d3c5d5bce9c6779a574ed2ba3d274d8016",
            "author matt jack <matteog@gmail.com> 1326214449 +0100",
            "committer matt jack <matteog@gmail.com> 1326214449 +0100",
            "",
            "    first commit"
        );

        $mockRepo = $this->getMockRepository();
        $this->addOutputToMockRepo($mockRepo, $outputLines);

        $commit = Commit::pick($mockRepo);
        $this->doCommitTest(
            $commit,
            'c277373174aa442af12a8e59de1812f3472c15f5',
            '9d36a2d3c5d5bce9c6779a574ed2ba3d274d8016',
            'matt jack',
            'matt jack',
            'matteog@gmail.com',
            'matteog@gmail.com',
            '1326214449',
            '1326214449',
            'first commit'
        );
    }

    /**
     * testCommitDate
     */
    public function testCommitDate()
    {
        $outputLines = array(
            "commit c277373174aa442af12a8e59de1812f3472c15f5",
            "tree c277373174aa442af12a8e59de1812f3472c15f6",
            "author John Doe <john.doe@example.org> 1326214449 +0100",
            "committer Jack Doe <jack.doe@example.org> 1326214449 +0100",
            "",
            "    First commit"
        );

        $mockRepo = $this->getMockRepository();
        $this->addOutputToMockRepo($mockRepo, $outputLines);

        $commit = Commit::pick($mockRepo);
        $this->doCommitTest(
            $commit,
            'c277373174aa442af12a8e59de1812f3472c15f5',
            'c277373174aa442af12a8e59de1812f3472c15f6',
            'John Doe',
            'Jack Doe',
            'john.doe@example.org',
            'jack.doe@example.org',
            '1326214449',
            '1326214449',
            'First commit'
        );
    }

    /**
     * testCreateFromOutputLines
     */
    public function testCreateFromOutputLines()
    {
        $outputLines = array(
            "commit c277373174aa442af12a8e59de1812f3472c15f5",
            "tree 9d36a2d3c5d5bce9c6779a574ed2ba3d274d8016",
            "author John Doe <john.doe@example.org> 1326214000 +0100",
            "committer Jack Doe <jack.doe@example.org> 1326214100 +0100",
            "",
            "    Initial commit"
        );

        $commit = Commit::createFromOutputLines($this->getRepository(), $outputLines);
        $this->doCommitTest(
            $commit,
            'c277373174aa442af12a8e59de1812f3472c15f5',
            '9d36a2d3c5d5bce9c6779a574ed2ba3d274d8016',
            'John Doe',
            'Jack Doe',
            'john.doe@example.org',
            'jack.doe@example.org',
            '1326214000',
            '1326214100',
            'Initial commit'
        );
    }

    /**
     * testCreate
     */
    public function testCreate()
    {
        $this->getRepository()->init();
        $this->addFile('test');
        $this->repository->stage();
        $commit = Commit::create($this->repository, 'first commit', true);
        $this->assertEquals($commit, $this->repository->getCommit());
    }

    /**
     * testGetDiff
     */
    public function testGetDiff()
    {
        $this->getRepository()->init();
        $this->addFile('test');
        $this->repository->stage();
        $commit = Commit::create($this->repository, 'first commit', true);
        $diff = $commit->getDiff();
        $this->assertInstanceOf('GitElephant\Objects\Diff\Diff', $diff);
        $this->assertCount(1, $diff);

        $this->addFile('test2');
        $this->addFile('test3');
        $this->repository->stage();
        $commit = Commit::create($this->repository, 'second commit', true);
        $diff = $commit->getDiff();
        $this->assertInstanceOf('GitElephant\Objects\Diff\Diff', $diff);
        $this->assertCount(2, $diff);
    }

    public function testRevParse()
    {
        $this->getRepository()->init();
        $this->addFile('test');
        $this->repository->stage();
        $commit = Commit::create($this->repository, 'first commit', true);

        $revParse = $commit->revParse();
        $this->assertEquals($commit->getSha(), $revParse[0]);
    }

    public function testCommitWithoutTag()
    {
        $this->getRepository()->init();
        $this->addFile('test');
        $this->repository->stage();
        $commit = Commit::create($this->repository, 'first commit', true);
        $this->assertFalse($commit->tagged());
    }

    public function testCommitWithTag()
    {
        $this->getRepository()->init();
        $this->addFile('test');
        $this->repository->stage();
        $commit = Commit::create($this->repository, 'first commit', true);
        Tag::create($this->repository, '1.0.0');
        $this->assertTrue($commit->tagged());
        $this->assertCount(1, $commit->getTags());
        $commitTags = $commit->getTags();
        $this->assertEquals('1.0.0', $commitTags[0]->getName());
    }

}

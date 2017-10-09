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
use \GitElephant\Objects\Log;
use \GitElephant\Command\LogCommand;

/**
 * LogTest
 *
 * @author Mathias Geat <mathias@ailoo.net>
 */
class LogTest extends TestCase
{
    /**
     * setUp
     */
    public function setUp()
    {
        $this->getRepository()->init();

        for ($i = 0; $i < 10; $i++) {
            $this->addFile('test file ' . $i);
            $this->getRepository()->commit('test commit index:' . $i, true);
        }
    }

    /**
     * testLogCountable
     */
    public function testLogCountable()
    {
        $log = $this->getRepository()->getLog();
        $this->assertEquals($log->count(), count($log));
    }

    /**
     * parents created by log
     */
    public function testParents()
    {
        $log = $this->getRepository()->getLog();
        $lastCommit = $this->repository->getCommit();
        $lastLogCommit = $log[0];
        $this->assertEquals($lastCommit->getParents(), $lastLogCommit->getParents());
        Branch::create($this->repository, 'new-branch');
        $this->getRepository()->checkout('new-branch');
        $this->addFile('another file');
        $this->repository->commit('another commit', true);
        $lastCommitOtherBranch = $this->getRepository()->getCommit();
        $this->getRepository()->checkout('master');
        $this->addFile('another file on master');
        $this->getRepository()->commit('new commit on master', true);
        $lastCommitOnMaster = $this->getRepository()->getCommit();
        $this->getRepository()->merge($this->getRepository()->getBranch('new-branch'));
        $log = $this->getRepository()->getLog();
        $lastLogCommit = $log[0];
        $this->assertContains($lastCommitOnMaster->getSha(), $lastLogCommit->getParents());
        $this->assertContains($lastCommitOtherBranch->getSha(), $lastLogCommit->getParents());
    }

    /**
     * testLogCountLimit
     */
    public function testLogCountLimit()
    {
        $log = $this->getRepository()->getLog(null, null, null);
        $this->assertEquals(10, $log->count());

        $log = $this->getRepository()->getLog(null, null, 10, null);
        $this->assertEquals(10, $log->count());

        $log = $this->getRepository()->getLog(null, null, 50, null);
        $this->assertEquals(10, $log->count());

        $log = $this->getRepository()->getLog(null, null, 60, null);
        $this->assertEquals(10, $log->count());

        $log = $this->getRepository()->getLog(null, null, 1, null);
        $this->assertEquals(1, $log->count());

        $log = $this->getRepository()->getLog(null, null, 0, null);
        $this->assertEquals(0, $log->count());

        $log = $this->getRepository()->getLog(null, null, -1, null);
        $this->assertEquals(10, $log->count());

        $log = $this->getRepository()->getLog(null, "test\ file\ 1", -1, null);
        $this->assertEquals(1, $log->count());

        $log = $this->getRepository()->getLog(null, "test\ file*", -1, null);
        $this->assertEquals(10, $log->count());
    }

    /**
     * testLogOffset
     */
    public function testLogOffset()
    {
        $log = $this->getRepository()->getLog(null, null, null, 0);
        $this->assertEquals(10, $log->count());

        $log = $this->getRepository()->getLog(null, null, null, 5);
        $this->assertEquals(5, $log->count());

        $log = $this->getRepository()->getLog(null, null, null, 50);
        $this->assertEquals(0, $log->count());

        $log = $this->getRepository()->getLog(null, null, null, 100);
        $this->assertEquals(0, $log->count());
    }

    /**
     * testLogIndex
     */
    public function testLogIndex()
    {
        $log = $this->getRepository()->getLog(null, null, null, null);

        // [0;50[ - 10 = 39
        $this->assertEquals('test commit index:7', $log[2]->getMessage()->toString());
        $this->assertEquals('test commit index:7', $log->index(2)->getMessage()->toString());
        $this->assertEquals('test commit index:7', $log->offsetGet(2)->getMessage()->toString());
    }

    /**
     * testLogToArray
     */
    public function testLogToArray()
    {
        $log = $this->getRepository()->getLog(null, null, null, null);

        $this->assertTrue(is_array($log->toArray()));
        $this->assertInternalType('array', $log->toArray());
        $this->assertEquals($log->count(), count($log->toArray()));
    }

    /**
     * testObjectLog
     */
    public function testObjectLog()
    {
        $tree = $this->getRepository()->getTree();
        $file = $tree[0];
    }

    /**
     * testLogCreatedFromOutputLines
     */
    public function testLogCreatedFromOutputLines()
    {
        $tree = $this->getRepository()->getTree();
        $obj = $tree[count($tree) - 1];
        $logCommand = new LogCommand();
        $command = $logCommand->showObjectLog($obj);
        $log = Log::createFromOutputLines($this->getRepository(), $this->caller->execute($command)->getOutputLines());
        $this->assertInstanceOf('GitElephant\Objects\Log', $log);
        $this->assertCount(1, $log);

    }
}

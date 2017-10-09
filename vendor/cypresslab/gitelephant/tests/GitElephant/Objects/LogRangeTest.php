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

use \GitElephant\Repository;
use \GitElephant\TestCase;
use \GitElephant\Objects\Log;
use \GitElephant\Command\LogCommand;

/**
 * LogRangeTest
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class LogRangeTest extends TestCase
{
    /**
     * @var Commit
     */
    private $firstCommit;

    /**
     * @var Commit
     */
    private $secondCommit;

    /**
     * @var Commit
     */
    private $lastCommit;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->initRepository(null, 0);
        $this->initRepository(null, 1);
        $r1 = $this->getRepository(0);
        $r1->init();

        for ($i = 0; $i < 10; $i++) {
            $this->addFile('test file ' . $i, null, null, $r1);
            $this->getRepository(0)->commit('test commit index:' . $i, true);
        }

        $log = $this->getRepository(0)->getLog();
        $this->firstCommit = $log[9];
        $this->lastCommit = $log[0];
        $this->secondCommit = $log[8];
    }

    public function testCreateFromCommand()
    {
        $logRange = new LogRange($this->getRepository(0), $this->firstCommit, $this->lastCommit);
        $this->assertInstanceOf('\ArrayAccess', $logRange);
        $this->assertInstanceOf('\Countable', $logRange);
        $this->assertInstanceOf('\Iterator', $logRange);
    }

    public function testToArray()
    {
        $logRange = new LogRange($this->getRepository(0), $this->firstCommit, $this->lastCommit);
        $this->assertInternalType('array', $logRange->toArray());
        $this->assertCount(9, $logRange->toArray());
    }

    public function testIndex()
    {
        $logRange = new LogRange($this->getRepository(0), $this->firstCommit, $this->lastCommit);
        $this->assertEquals($this->lastCommit, $logRange->index(0));
    }

    public function testArrayAccess()
    {
        $logRange = new LogRange($this->getRepository(0), $this->firstCommit, $this->lastCommit);
        $this->assertEquals($this->lastCommit, $logRange->first());
        $this->assertEquals($this->secondCommit, $logRange->last());
        $this->assertEquals($this->lastCommit, $logRange[0]);
        $this->assertEquals($this->secondCommit, $logRange[8]);
        $this->assertTrue(isset($logRange[0]));
        foreach ($logRange as $key => $commit) {
            $this->assertInstanceOf('GitElephant\Objects\Commit', $commit);
            $this->assertInternalType('int', $key);
        }
        $r = $this->getRepository(1);
        $logRange->setRepository($r);
        $this->assertEquals($r, $logRange->getRepository());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testExceptionOnSet()
    {
        $logRange = new LogRange($this->getRepository(0), $this->firstCommit, $this->lastCommit);
        $logRange[9] = 'test';
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testExceptionOnUnset()
    {
        $logRange = new LogRange($this->getRepository(0), $this->firstCommit, $this->lastCommit);
        unset($logRange[0]);
    }
}

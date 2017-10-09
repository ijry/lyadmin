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

namespace GitElephant;

use \GitElephant\Command\LsTreeCommand;
use \GitElephant\TestCase;

/**
 * LsTreeCommandTest
 *
 * LsTreeCommand class tests
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class LsTreeCommandTest extends TestCase
{
    /**
     * @var \GitElephant\Command\LsTreeCommand;
     */
    private $lsTreeCommand;

    /**
     * setUp function
     */
    public function setUp()
    {
        $this->lsTreeCommand = new LsTreeCommand();
    }

    /**
     * fullTree test
     *
     * @covers \GitElephant\Command\LsTreeCommand::tree
     */
    public function testFullTree()
    {
        $this->assertEquals("ls-tree '-r' '-t' '-l' 'HEAD'", $this->lsTreeCommand->fullTree(), 'ls-tree command test');
    }

    /**
     * tree test
     *
     * @covers \GitElephant\Command\LsTreeCommand::tree
     */
    public function testTree()
    {
        $this->assertEquals("ls-tree '-l' 'HEAD'", $this->lsTreeCommand->tree(), 'ls-tree command test');
    }

    /**
     * listAll test
     *
     * @covers \GitElephant\Command\LsTreeCommand::listAll
     */
    public function testListAll()
    {
        $this->assertEquals("ls-tree 'HEAD'", $this->lsTreeCommand->listAll(), 'ls-tree command test');
    }
}

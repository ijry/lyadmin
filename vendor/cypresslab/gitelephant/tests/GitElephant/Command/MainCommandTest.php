<?php

/**
 * This file is part of the GitElephant package.
 *
 * (c) M
 * atteo Giachino <matteog@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Just for fun...
 */

namespace GitElephant;

use \GitElephant\TestCase;
use \GitElephant\Command\MainCommand;

/**
 * MainTest
 *
 * MainCommand class tests
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
 
class MainCommandTest extends TestCase
{
    /**
     * @var \GitElephant\Command\MainCommand;
     */
    private $mainCommand;

    /**
     * setup
     */
    public function setUp()
    {
        $this->mainCommand = new MainCommand();
    }

    /**
     * init test
     */
    public function testInit()
    {
        $this->assertEquals(MainCommand::GIT_INIT, $this->mainCommand->init());
        $this->assertEquals(MainCommand::GIT_INIT." '--bare'", $this->mainCommand->init(true));
    }

    /**
     * status test
     */
    public function testStatus()
    {
        $this->assertEquals("'-c' 'color.status'='false' ".MainCommand::GIT_STATUS, $this->mainCommand->status());
    }

    /**
     * add test
     */
    public function testAdd()
    {
        $this->assertEquals(MainCommand::GIT_ADD." '--all' '.'", $this->mainCommand->add());
        $this->assertEquals(MainCommand::GIT_ADD." '--all' 'foo'", $this->mainCommand->add('foo'));
    }

    /**
     * unstage test
     */
    public function testUnstage()
    {
        $this->assertEquals(MainCommand::GIT_RESET." 'HEAD' -- 'foo'", $this->mainCommand->unstage('foo'));
    }

    /**
     * commit test
     */
    public function testCommit()
    {
        $this->assertEquals(MainCommand::GIT_COMMIT." '-m' 'foo'", $this->mainCommand->commit('foo'));
        $this->assertEquals(MainCommand::GIT_COMMIT." '-a' '-m' 'foo'", $this->mainCommand->commit('foo', true));
        $this->assertEquals(MainCommand::GIT_COMMIT." '--author' 'example <example@example.com>' '-m' 'foo'",
            $this->mainCommand->commit('foo', false, 'example <example@example.com>'));
        $this->assertEquals(MainCommand::GIT_COMMIT." '-a' '--author' 'example <example@example.com>' '-m' 'foo'",
            $this->mainCommand->commit('foo', true, 'example <example@example.com>'));
        $this->assertEquals(MainCommand::GIT_COMMIT." '--author' 'example <example@example.com>' '--allow-empty' '-m' 'foo'",
          $this->mainCommand->commit('foo', false, 'example <example@example.com>', true));
    }

    /**
     * checkout test
     */
    public function testCheckout()
    {
        $this->assertEquals(MainCommand::GIT_CHECKOUT." '-q' 'master'", $this->mainCommand->checkout('master'));
    }
}

<?php
/**
 * GitElephant - An abstraction layer for git written in PHP
 * Copyright (C) 2013  Matteo Giachino
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see [http://www.gnu.org/licenses/].
 */

namespace GitElephant\Command;

use \GitElephant\Objects\Branch;
use \GitElephant\Objects\Remote;
use \GitElephant\TestCase;
use \GitElephant\Objects\Commit;
use \Mockery as m;

/**
 * CloneCommandTest
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class FetchCommandTest extends TestCase
{
    /**
     * set up
     */
    public function setUp()
    {
        $this->initRepository();
        $this->getRepository()->init();
        $this->addFile('test');
        $this->getRepository()->commit('test', true);
    }

    /**
     * fetch test
     */
    public function testFetch()
    {
        $fc = FetchCommand::getInstance();
        $this->assertEquals("fetch", $fc->fetch());
        $this->assertEquals("fetch 'github'", $fc->fetch('github'));
        $this->assertEquals("fetch 'github' 'develop'", $fc->fetch('github', 'develop'));
        $this->getRepository()->addRemote('test-remote', 'git@github.com:matteosister/GitElephant.git');
        $remote = m::mock('GitElephant\Objects\Remote')
            ->shouldReceive('getName')->andReturn('test-remote')->getMock();
        $this->assertEquals("fetch 'test-remote' 'develop'", $fc->fetch($remote, 'develop'));
        $branch = Branch::create($this->getRepository(), 'test-branch');
        $this->assertEquals("fetch 'test-remote' 'test-branch'", $fc->fetch($remote, $branch));
        $this->assertEquals("fetch '--tags' 'test-remote' 'test-branch'", $fc->fetch($remote, $branch, array('--tags')));
    }
}

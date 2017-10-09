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

namespace GitElephant\Command;

use \GitElephant\Command\LogCommand;
use \GitElephant\TestCase;

/**
 * DiffTreeCommandTest
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class LogCommandTest extends TestCase
{
    /**
     * set up
     */
    public function setUp()
    {
        $this->initRepository();
        $this->getRepository()->init();
        $this->addFile('foo');
        $this->addFolder('test-folder');
        $this->addFile('test-file', 'test-folder', 'test');
        $this->getRepository()->commit('first commit', true);
    }

    /**
     * testShowObjectLog
     */
    public function testShowObjectLog()
    {
        $branch = $this->getRepository()->getBranch('master');
        $obj = $this->getRepository()->getTree('HEAD', 'test-folder/test-file')->getBlob();
        $lc = LogCommand::getInstance();
        $this->assertEquals(
            "log '-s' '--pretty=raw' '--no-color' -- 'test-folder/test-file'",
            $lc->showObjectLog($obj)
        );
        $this->assertEquals(
            "log '-s' '--pretty=raw' '--no-color' 'master' -- 'test-folder/test-file'",
            $lc->showObjectLog($obj, $branch)
        );
    }
}

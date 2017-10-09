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

use \GitElephant\Command\CloneCommand;
use \GitElephant\TestCase;
use \GitElephant\Objects\Commit;

/**
 * CloneCommandTest
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class CloneCommandTest extends TestCase
{
    /**
     * set up
     */
    public function setUp()
    {
        $this->initRepository();
    }

    /**
     * set up
     */
    public function testCloneUrl()
    {
        $cc = CloneCommand::getInstance();
        $this->assertEquals(
            "clone 'git://github.com/matteosister/GitElephant.git'",
            $cc->cloneUrl('git://github.com/matteosister/GitElephant.git')
        );
        $this->assertEquals(
            "clone 'git://github.com/matteosister/GitElephant.git' 'test'",
            $cc->cloneUrl('git://github.com/matteosister/GitElephant.git', 'test')
        );
    }
}

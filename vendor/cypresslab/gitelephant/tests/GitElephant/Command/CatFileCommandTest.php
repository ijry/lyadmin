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

use \GitElephant\Command\BranchCommand;
use \GitElephant\TestCase;

/**
 * BranchTest
 *
 * Branch test
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class CatFileCommandTest extends TestCase
{
    /**
     * setUp, called on every method
     */
    public function setUp()
    {
        $this->initRepository();
        $this->getRepository()->init();
        $this->addFile('test', null, 'test content');
        $this->addFolder('test-folder');
        $this->addFile('test2', 'test-folder', 'test content 2');
        $this->getRepository()->commit('first commit', true);
    }

    /**
     * CatFileCommand::content()
     */
    public function testContent()
    {
        $cfc = new CatFileCommand();
        $master = $this->getRepository()->getBranch('master');
        $tree = $this->getRepository()->getTree('HEAD', 'test-folder/test2');
        $this->assertEquals(
            sprintf(
                "cat-file '-p' '%s:test-folder/test2'",
                $master->getSha()
            ),
            $cfc->content($tree->getBlob(), $master)
        );
        $this->getRepository()->createTag('test-tag');
        $tag = $this->getRepository()->getTag('test-tag');
        $this->assertEquals(
            sprintf(
                "cat-file '-p' '%s:test-folder/test2'",
                $tag->getSha()
            ),
            $cfc->content($tree->getBlob(), $tag)
        );
        $this->assertEquals(
            sprintf(
                "cat-file '-p' '%s:test-folder/test2'",
                $tag->getSha()
            ),
            $cfc->content(
                $tree->getBlob(),
                $tag->getSha()
            )
        );
    }
}

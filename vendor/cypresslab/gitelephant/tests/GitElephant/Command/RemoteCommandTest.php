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

use \GitElephant\TestCase;

/**
 * Class RemoteCommandTest
 *
 * @package GitElephant\Command
 * @author  David Neimeyer <davidneimeyer@gmail.com>
 */
class RemoteCommandTest extends TestCase
{
    protected $startBranchName = 'test_branch';
    protected $startTagName = 'test_start_tag';

    /**
     * setUp
     */
    public function setUp()
    {
        $this->initRepository();
        $repo = $this->getRepository();
        $repo->init();
        $this->addFile('test');
        $repo->commit('test', true);
        $repo->createTag($this->startTagName);
        $repo->createBranch($this->startBranchName);
        $repo->checkout($this->startBranchName);
    }

    /**
     * Validate generated command when no arguements and no repository are used
     */
    public function testRemoteNoArgs()
    {
        $actual = RemoteCommand::getInstance()->remote();
        $expected = "remote";
        $this->assertEquals($expected, $actual, 'remote() without arguments is just the remote command');
    }

    /**
     * validate git-remote show [name]
     */
    public function testShow()
    {
        $remotename = 'foobar';
        $actual = RemoteCommand::getInstance()->show($remotename);
        $expected = "remote show '$remotename'";
        $this->assertEquals($expected, $actual, 'show() builds remote command with show subcommand');

        $actual = RemoteCommand::getInstance()->show($remotename, false);
        $expected = "remote show '-n' '$remotename'";
        $this->assertEquals($expected, $actual, 'show(, true) builds remote command with show subcommand and -n flag');
    }

    /**
     * validate git-remote --verbose
     */
    public function testVerbose()
    {
        $actual = RemoteCommand::getInstance()->verbose();
        $expected = "remote '--verbose'";
        $this->assertEquals($expected, $actual, 'show() builds remote command with show subcommand');
    }

    /**
     * validate git-remote add [options] <name> <url>
     */
    public function testAdd()
    {
        $name = 'foobar';
        $url = 'git@foobar.com:/Foo/Bar.git';
        $options = array(
            '-t bazblurg',
            '--mirror=fetch',
            '--tags'
        );
        $actual = RemoteCommand::getInstance()->add($name, $url, $options);
        $expected = "remote add '-t' 'bazblurg' '--mirror=fetch' '--tags' '$name' '$url'";
        $this->assertEquals($expected, $actual, 'add() builds remote command with add subcommand');
    }
}

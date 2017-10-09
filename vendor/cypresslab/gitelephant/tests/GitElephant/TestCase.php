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

use \GitElephant\Command\MvCommand;
use \GitElephant\Repository;
use \GitElephant\GitBinary;
use \GitElephant\Command\Caller\Caller;
use \GitElephant\Objects\Commit;
use \Symfony\Component\Finder\Finder;
use \Symfony\Component\Filesystem\Filesystem;
use \Mockery as m;

/**
 * Class TestCase
 *
 * @package GitElephant
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \GitElephant\Command\Caller\CallerInterface
     */
    protected $caller;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @param null $name
     *
     * @return \GitElephant\Repository
     */
    protected function getRepository($name = null)
    {
        if ($this->repository == null) {
            $this->initRepository($name);
        }
        if (is_null($name)) {
            return $this->repository;
        } else {
            return $this->repository[$name];
        }
    }

    /**
     * @return \GitElephant\Command\Caller\Caller
     */
    protected function getCaller()
    {
        if ($this->caller == null) {
            $this->initRepository();
        }

        return $this->caller;
    }

    /**
     * @param null|string $name  the folder name
     * @param int         $index the repository index (for getting them back)
     *
     * @return void
     */
    protected function initRepository($name = null, $index = null)
    {
        $tempDir = realpath(sys_get_temp_dir());
        $tempName = null === $name ? tempnam($tempDir, 'gitelephant') : $tempDir.DIRECTORY_SEPARATOR.$name;
        $this->path = $tempName;
        @unlink($this->path);
        $fs = new Filesystem();
        $fs->mkdir($this->path);
        $this->caller = new Caller(new GitBinary(), $this->path);
        if (is_null($index)) {
            $this->repository = Repository::open($this->path);
            $this->assertInstanceOf('GitElephant\Repository', $this->repository);
        } else {
            if (!is_array($this->repository)) {
                $this->repository = array();
            }
            $this->repository[$index] = Repository::open($this->path);
            $this->assertInstanceOf('GitElephant\Repository', $this->repository[$index]);
        }
    }

    protected function tearDown()
    {
        $fs = new Filesystem();
        if (is_array($this->repository)) {
            array_map(function (Repository $repo) use ($fs) {
                $fs->remove($repo->getPath());
            }, $this->repository);
        } else {
            $fs->remove($this->path);
        }
        m::close();
    }

    /**
     * @param string      $name       file name
     * @param string|null $folder     folder name
     * @param null        $content    content
     * @param Repository  $repository repository to add file to
     *
     * @return void
     */
    protected function addFile($name, $folder = null, $content = null, $repository = null)
    {
        if (is_null($repository)) {
            $path = $this->path;
        } else {
            $path = $repository->getPath();
        }
        $filename = $folder == null ?
            $path.DIRECTORY_SEPARATOR.$name :
            $path.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$name;
        $handle = fopen($filename, 'w');
        $fileContent = $content == null ? 'test content' : $content;
        $this->assertTrue(false !== fwrite($handle, $fileContent), sprintf('unable to write the file %s', $name));
        fclose($handle);
    }

    /**
     * remove file from repo
     *
     * @param string $name
     */
    protected function removeFile($name)
    {
        $filename = $this->path.DIRECTORY_SEPARATOR.$name;
        $this->assertTrue(unlink($filename));
    }

    /**
     * update a file in the repository
     *
     * @param string $name    file name
     * @param string $content content
     */
    protected function updateFile($name, $content)
    {
        $filename = $this->path.DIRECTORY_SEPARATOR.$name;
        $this->assertTrue(false !== file_put_contents($filename, $content));
    }

    /**
     * rename a file in the repository
     *
     * @param string $originName file name
     * @param string $targetName new file name
     * @param bool   $gitMv      use git mv, otherwise uses php rename function (with the Filesystem component)
     */
    protected function renameFile($originName, $targetName, $gitMv = true)
    {
        if ($gitMv) {
            $this->getRepository()->getCaller()->execute(MvCommand::getInstance()->rename($originName, $targetName));

            return;
        }
        $origin = $this->path.DIRECTORY_SEPARATOR.$originName;
        $target = $this->path.DIRECTORY_SEPARATOR.$targetName;
        $fs = new Filesystem();
        $fs->rename($origin, $target);
    }

    /**
     * @param string $name name
     *
     * @return void
     */
    protected function addFolder($name)
    {
        $fs = new Filesystem();
        $fs->mkdir($this->path.DIRECTORY_SEPARATOR.$name);
    }

    protected function addSubmodule($url, $path)
    {
        $this->getRepository()->addSubmodule($url, $path);
    }

    /**
     * mock the caller
     *
     * @param string $command command
     * @param string $output  output
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockCaller($command, $output)
    {
        $mock = $this->getMock('GitElephant\Command\Caller\CallerInterface');
        $mock
            ->expects($this->any())
            ->method('execute')
            ->will($this->returnValue($mock));
        $mock
            ->expects($this->any())
            ->method('getOutputLines')
            ->will($this->returnValue($output));

        return $mock;
    }

    protected function getMockContainer()
    {
        return $this->getMock('GitElephant\Command\CommandContainer');
    }

    protected function addCommandToMockContainer(\PHPUnit_Framework_MockObject_MockObject $container, $commandName)
    {
        $container
            ->expects($this->any())
            ->method('get')
            ->with($this->equalTo($commandName))
            ->will($this->returnValue($this->getMockCommand()));
    }

    protected function addOutputToMockRepo(\PHPUnit_Framework_MockObject_MockObject $repo, $output)
    {
        $repo
            ->expects($this->any())
            ->method('getCaller')
            ->will($this->returnValue($this->getMockCaller('', $output)));
    }

    protected function getMockCommand()
    {
        $command = $this->getMock('Command', array('showCommit'));
        $command
            ->expects($this->any())
            ->method('showCommit')
            ->will($this->returnValue(''));

        return $command;
    }

    protected function getMockRepository()
    {
        return $this->getMock(
            'GitElephant\Repository',
            array(),
            array(
                $this->repository->getPath(),
                $this->getMockBinary()
            )
        );
    }

    protected function getMockBinary()
    {
        return $this->getMock('GitElephant\GitBinary');
    }

    protected function doCommitTest(
        Commit $commit,
        $sha,
        $tree,
        $author,
        $committer,
        $emailAuthor,
        $emailCommitter,
        $datetimeAuthor,
        $datetimeCommitter,
        $message
    ) {
        $this->assertInstanceOf('GitElephant\Objects\Commit', $commit);
        $this->assertEquals($sha, $commit->getSha());
        $this->assertEquals($tree, $commit->getTree());
        $this->assertInstanceOf('GitElephant\Objects\Author', $commit->getAuthor());
        $this->assertEquals($author, $commit->getAuthor()->getName());
        $this->assertEquals($emailAuthor, $commit->getAuthor()->getEmail());
        $this->assertInstanceOf('GitElephant\Objects\Author', $commit->getCommitter());
        $this->assertEquals($committer, $commit->getCommitter()->getName());
        $this->assertEquals($emailCommitter, $commit->getCommitter()->getEmail());
        $this->assertInstanceOf('\Datetime', $commit->getDatetimeAuthor());
        $this->assertEquals($datetimeAuthor, $commit->getDatetimeAuthor()->format('U'));
        $this->assertInstanceOf('\Datetime', $commit->getDatetimeCommitter());
        $this->assertEquals($datetimeCommitter, $commit->getDatetimeCommitter()->format('U'));
        $this->assertEquals($message, $commit->getMessage()->getShortMessage());
    }
}

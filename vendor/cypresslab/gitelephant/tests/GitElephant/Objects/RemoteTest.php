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
use \GitElephant\Objects\Remote;

/**
 * Class RemoteTest
 * 
 * Remote Object Test
 * 
 * @package GitElephant\Command
 * @author  David Neimeyer <davidneimeyer@gmail.com>
 */
class RemoteTest extends TestCase
{
    /**
     * test double branch name
     * @var string
     */
    protected $startBranchName = 'test_branch';

    /**
     * test double tag name
     * @var string
     */
    protected $startTagName = 'test_start_tag';

    /**
     * test setup
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
     * return sample output of git-remote --verbose
     * @return string
     */
    public function sampleRemoteVerbose()
    {
        $name = $this->sampleRemoteShowRemoteName();
        $fetch = $this->sampleRemoteShowFetchURL();
        $push = $this->sampleRemoteShowPushURL();

        return <<<EOM
origin	originFoo (fetch)
origin	originBar (push)
{$name}	{$fetch} (fetch)
{$name}	{$push} (push)
EOM;
    }

    /**
     * test double fetch URL
     * @return string
     */
    public function sampleRemoteShowFetchURL()
    {
        return 'git@foobar.baz:blurg/burpfetch.git';
    }

    /**
     * test double push URL
     * @return string
     */
    public function sampleRemoteShowPushURL()
    {
        return 'git@foobar.baz:blurg/burppush.git';
    }

    /**
     * test double remote name
     * @return string
     */
    public function sampleRemoteShowRemoteName()
    {
        return 'delphi';
    }

    /**
     * test double remote HEAD branch
     * @return string
     */
    public function sampleRemoteShowRemoteHEAD()
    {
        'Apollo';
    }

    /**
     * sample output of git-remote show <remoteName> 
     * @return string
     */
    public function sampleRemoteShow()
    {
        $name = $this->sampleRemoteShowRemoteName();
        $fetch = $this->sampleRemoteShowFetchURL();
        $push = $this->sampleRemoteShowPushURL();
        $head = $this->sampleRemoteShowRemoteHEAD();

        return <<<EOM
* remote {$name}
  Fetch URL: {$fetch}
  Push  URL: {$push}
  HEAD branch: {$head}
  Remote branches:
    11.30.6                                        tracked
    11.32                                          tracked
    11.32.0                                        tracked
    11.40                                          tracked
    master                                         tracked
    pbi_4371                                       tracked
    refs/remotes/upstream/58120-squashed           stale (use 'git remote prune' to remove)
  Local branches configured for 'git pull':
    11.30.6 merges with remote 11.30.6
    11.32.0 merges with remote 11.32.0
    11.40   merges with remote 11.40
    master  merges with remote master
  Local refs configured for 'git push':
    11.30   pushes to 11.30   (local out of date)
    11.30.6 pushes to 11.30.6 (up to date)
    11.32   pushes to 11.32   (local out of date)
    11.32.0 pushes to 11.32.0 (up to date)
    11.40   pushes to 11.40   (local out of date)
    master  pushes to master  (up to date)
EOM;
    }

    /**
     * expected branch structure produced when
     * parsing the sample output of git-remote show <remoteName>
     * @return array
     */
    public function sampleRemoteShowAsArray()
    {
        return array(
                '11.30' => array(
                        'pushes_to' => '11.30',
                        'local_state' => '(local out of date)',
                ),
                '11.30.6' => array(
                        'pushes_to' => '11.30.6',
                        'local_state' => '(up to date)',
                        'merges_with' => '11.30.6',
                        'local_relationship' => 'tracked',
                ),
                '11.32.0' => array(
                        'pushes_to' => '11.32.0',
                        'local_state' => '(up to date)',
                        'merges_with' => '11.32.0',
                        'local_relationship' => 'tracked',
                ),
                '11.32' => array(
                        'pushes_to' => '11.32',
                        'local_state' => '(local out of date)',
                        'local_relationship' => 'tracked',
                ),
                '11.40' => array(
                        'pushes_to' => '11.40',
                        'merges_with' => '11.40',
                        'local_state' => '(local out of date)',
                        'local_relationship' => 'tracked',
                ),
                'master' => array(
                        'pushes_to' => 'master',
                        'local_state' => '(up to date)',
                        'merges_with' => 'master',
                        'local_relationship' => 'tracked',
                ),
                'pbi_4371' => array(
                        'local_relationship' => 'tracked',
                ),
                'refs/remotes/upstream/58120-squashed' => array(
                        'local_relationship' => 'stale',
                ),
        );
    }

    /**
     * test name getter
     */
    public function testGetName()
    {
        $sample = $this->sampleRemoteShow();
        $output = explode("\n", $sample);
        $remote = new Remote($this->getRepository());
        $remote->parseOutputLines($output);
        $actual = $remote->getName();
        $expected = $this->sampleRemoteShowRemoteName();
        $this->assertEquals($expected, $actual, 'parseOutputLines() proper digests git-remote show <remote> name');
    }

    /**
     * test name setter
     */
    public function testSetName()
    {
        $expected = 'foobar';
        $remote = new Remote($this->getRepository());
        $remote->setName($expected);
        $actual = $remote->getName();
        $this->assertEquals($expected, $actual, 'can set remote name');
    }

    /**
     * test fetch URL getter
     */
    public function testGetFetchURL()
    {
        $sample = $this->sampleRemoteShow();
        $output = explode("\n", $sample);
        $remote = new Remote($this->getRepository());
        $remote->parseOutputLines($output);
        $actual = $remote->getFetchURL();
        $expected = $this->sampleRemoteShowFetchURL();
        $this->assertEquals($expected, $actual, 'parseOutputLines() proper digests git-remote show <remote> fetch URL');
    }

    /**
     * test fetch URL setter
     */
    public function testSetFetchURL()
    {
        $expected = 'foobar';
        $remote = new Remote($this->getRepository());
        $remote->setFetchURL($expected);
        $actual = $remote->getFetchURL();
        $this->assertEquals($expected, $actual, 'can set fetch URL property');
    }

    /**
     * test push URL getter
     */
    public function testGetPushURL()
    {
        $sample = $this->sampleRemoteShow();
        $output = explode("\n", $sample);
        $remote = new Remote($this->getRepository());
        $remote->parseOutputLines($output);
        $actual = $remote->getPushURL();
        $expected = $this->sampleRemoteShowPushURL();
        $this->assertEquals(
            $expected,
            $actual,
            'parseOutputLines() proper digests git-remote show <remote> push URL'
        );
    }

    /**
     * test push URL setter
     */
    public function testSetPushURL()
    {
        $expected = 'foobar';
        $remote = new Remote($this->getRepository());
        $remote->setPushURL($expected);
        $actual = $remote->getPushURL();
        $this->assertEquals($expected, $actual, 'can set push URL property');
    }

    /**
     * test remote HEAD branch getter
     */
    public function testGetRemoteHEAD()
    {
        $sample = $this->sampleRemoteShow();
        $output = explode("\n", $sample);
        $remote = new Remote($this->getRepository());
        $remote->parseOutputLines($output);
        $actual = $remote->getRemoteHEAD();
        $expected = $this->sampleRemoteShowRemoteHEAD();
        $this->assertEquals(
            $expected,
            $actual,
            'parseOutputLines() proper digests git-remote show <remote> remote HEAD'
        );
    }

    /**
     * test remote HEAD branch setter
     */
    public function testSetRemoteHEAD()
    {
        $expected = 'foobar';
        $remote = new Remote($this->getRepository());
        $remote->setRemoteHEAD($expected);
        $actual = $remote->getRemoteHEAD();
        $this->assertEquals($expected, $actual, 'can set remote HEAD property');
    }

    /**
     * test branch detail parsing
     */
    public function testParseOutputLines()
    {
        $sample = $this->sampleRemoteShow();
        $output = explode("\n", $sample);
        $remote = new Remote($this->getRepository());
        $remote->parseOutputLines($output);
        $actual = $remote->getBranches();
        $expected = $this->sampleRemoteShowAsArray();
        $this->assertEquals(
            $expected,
            $actual,
            'parseOutputLines() proper digests git-remote show <remote> branch references'
        );
    }

    /**
     * helper for getting a mock Remote object
     * 
     * the returned test double will provide the sample output
     * defined in other methods of this test class
     * 
     * NOTE: this will do an assertion in the hope to ensure
     * sanity of the test double
     * 
     * @return \GitElephant\Objects\Remote
     */
    public function getMockRemote()
    {
        $sample = $this->sampleRemoteShow();
        $showOutput = explode("\n", $sample);
        $sample = $this->sampleRemoteVerbose();
        $verboseOutput = explode("\n", $sample);

        $mockRemote = $this->getMock(
            '\\GitElephant\\Objects\\Remote', //class
            array('getShowOutput', 'getVerboseOutput'), //methods to mock
            array(), //original constructor args
            '', //class for test double
            false //call constructor
        );

        $mockRemote->expects($this->any())
            ->method('getShowOutput')
            ->will($this->returnValue($showOutput));
        $mockRemote->expects($this->any())
            ->method('getVerboseOutput')
            ->will($this->returnValue($verboseOutput));

        $name = $this->sampleRemoteShowRemoteName();
        $mockRemote->__construct($this->getRepository(), $name);
        $this->assertEquals(
            $this->sampleRemoteShowRemoteName(),
            $mockRemote->getName(),
            'able to create mock object with properly parsed sample data'
        );

        return $mockRemote;
    }

    /**
     * verify object
     * 
     * NOTE: this is actually verifying the mock object and
     * could be useless if the helper that makes the double
     * is flawed.  However, it should do the trick so other tests
     * that depend on the double will have supporting test data/failures
     */
    public function testConstructor()
    {
        $obj = $this->getMockRemote();
        $this->assertInstanceOf('\\GitElephant\\Objects\\Remote', $obj);
    }

    /**
     * verify name is produced with cast to a string
     */
    public function testToString()
    {
        $obj = $this->getMockRemote();
        $this->assertEquals(
            $this->sampleRemoteShowRemoteName(),
            (string) $obj,
            'magic to string method provides the remote name'
        );
    }

    /**
     * verify that we always get an array, even if empty, when getting
     * data from the underlying implementation
     */
    public function testGetVerboseOutputReturnArray()
    {
        $remote = new Remote($this->getRepository());
        $actual = $remote->getVerboseOutput();
        $this->assertTrue(is_array($actual), 'getVerboseOutput() returns array');
    }

    /**
     * verify that we always get an array, even if empty, when getting
     * data from the underlying implementation
     */
    public function testGetShowOutputReturnArray()
    {
        $remote = new Remote($this->getRepository());
        $actual = $remote->getShowOutput();
        $this->assertTrue(is_array($actual), 'getShowOutput() returns array');
    }
}

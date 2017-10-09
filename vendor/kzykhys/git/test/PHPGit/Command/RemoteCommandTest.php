<?php

use PHPGit\Git;

require_once __DIR__ . '/../BaseTestCase.php';

class RemoteCommandTest extends BaseTestCase
{

    public function testRemote()
    {
        $git = new Git();
        $git->clone('https://github.com/kzykhys/Text.git', $this->directory);
        $git->setRepository($this->directory);

        $remotes = $git->remote();

        $this->assertEquals(array(
            'origin' => array(
                'fetch' => 'https://github.com/kzykhys/Text.git',
                'push'  => 'https://github.com/kzykhys/Text.git'
            )
        ), $remotes);
    }

    public function testRemoteAdd()
    {
        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');

        $remotes = $git->remote();

        $this->assertEquals(array(
            'origin' => array(
                'fetch' => 'https://github.com/kzykhys/Text.git',
                'push'  => 'https://github.com/kzykhys/Text.git'
            )
        ), $remotes);
    }

    public function testRemoteRename()
    {
        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
        $git->remote->rename('origin', 'upstream');

        $remotes = $git->remote();
        $this->assertEquals(array(
            'upstream' => array(
                'fetch' => 'https://github.com/kzykhys/Text.git',
                'push'  => 'https://github.com/kzykhys/Text.git'
            )
        ), $remotes);
    }

    public function testRemoteRm()
    {
        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
        $git->remote->rm('origin');

        $remotes = $git->remote();
        $this->assertEquals(array(), $remotes);
    }

    public function testRemoteShow()
    {
        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');

        $this->assertNotEmpty($git->remote->show('origin'));
    }

    public function testRemotePrune()
    {
        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);
        $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
        $git->remote->prune('origin');
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testBadMethodCall()
    {
        $git = new Git();
        $git->remote->foo();
    }

} 
<?php

use PHPGit\Git;

require_once __DIR__ . '/../../BaseTestCase.php';

class SetHeadCommandTest extends BaseTestCase
{

    public function testSetHead()
    {
        $git = new Git();
        $git->clone('https://github.com/kzykhys/Text.git', $this->directory);
        $git->setRepository($this->directory);

        $before = $git->branch(array('all' => true));

        $git->remote->head('origin', 'master');

        $after = $git->branch(array('all' => true));

        $this->assertEquals($before, $after);
    }

    public function testSetHeadDelete()
    {
        $git = new Git();
        $git->clone('https://github.com/kzykhys/Text.git', $this->directory);
        $git->setRepository($this->directory);

        $before = $git->branch(array('all' => true));

        $git->remote->head->delete('origin');

        $after = $git->branch(array('all' => true));

        $this->assertNotEquals($before, $after);
    }

    public function testSetHeadRemote()
    {
        $git = new Git();
        $git->clone('https://github.com/kzykhys/Text.git', $this->directory);
        $git->setRepository($this->directory);

        $before = $git->branch(array('all' => true));

        $git->remote->head->delete('origin');
        $git->remote->head->remote('origin');

        $after = $git->branch(array('all' => true));

        $this->assertEquals($before, $after);
    }

} 
<?php

use PHPGit\Git;

require_once __DIR__ . '/../BaseTestCase.php';

class FetchCommandTest extends BaseTestCase
{

    public function testFetch()
    {
        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
        $git->fetch('origin', '+refs/heads/*:refs/remotes/origin/*');

        $tags = $git->tag();
        $this->assertContains('v1.0.0', $tags);
    }

    public function testFetchAll()
    {
        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
        $git->fetch->all();

        $tags = $git->tag();
        $this->assertContains('v1.0.0', $tags);
    }

} 
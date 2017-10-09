<?php

use PHPGit\Git;

require_once __DIR__ . '/../../BaseTestCase.php';

class SetBranchesCommandTest extends BaseTestCase
{

    public function testSetBranches()
    {
        $git = new Git();
        $git->clone('https://github.com/kzykhys/Text.git', $this->directory);
        $git->setRepository($this->directory);

        $git->remote->branches('origin', array('master'));
    }

    public function testSetBranchesAdd()
    {
        $git = new Git();
        $git->clone('https://github.com/kzykhys/Text.git', $this->directory);
        $git->setRepository($this->directory);

        $git->remote->branches->add('origin', array('gh-pages'));
    }

} 
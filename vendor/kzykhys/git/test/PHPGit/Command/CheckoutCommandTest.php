<?php

use PHPGit\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';

class CheckoutCommandTest extends BaseTestCase
{

    public function setUp()
    {
        parent::setUp();

        $filesystem = new Filesystem();

        $git = new Git();
        $git->init($this->directory);
        $git->setRepository($this->directory);

        $filesystem->dumpFile($this->directory . '/test.txt', '');
        $git->add('test.txt');
        $git->commit('Initial commit');
    }

    public function testCheckout()
    {
        $git = new Git();
        $git->setRepository($this->directory);
        $git->branch->create('next');
        $git->checkout('next');

        $branches = $git->branch();
        $this->assertArrayHasKey('next', $branches);
        $this->assertTrue($branches['next']['current']);
    }

    public function testCheckoutCreate()
    {
        $git = new Git();
        $git->setRepository($this->directory);
        $git->checkout->create('next');

        $branches = $git->branch();
        $this->assertArrayHasKey('next', $branches);
        $this->assertTrue($branches['next']['current']);

        $git->checkout->create('develop', 'next');

        $branches = $git->branch();
        $this->assertArrayHasKey('develop', $branches);
        $this->assertTrue($branches['develop']['current']);
    }

    public function testCheckoutOrphan()
    {
        $git = new Git();
        $git->setRepository($this->directory);
        $git->checkout->orphan('gh-pages', 'master', array('force' => true));

        $status = $git->status();
        $this->assertEquals('gh-pages', $status['branch']);
    }

} 
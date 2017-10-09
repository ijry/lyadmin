<?php

use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Kazuyuki Hayashi <hayashi@siance.co.jp>
 */
abstract class BaseTestCase extends PHPUnit_Framework_TestCase
{

    /**
     * @var string
     */
    protected $directory;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->directory = __DIR__.'/../../build/' . strtolower(get_class($this));
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->directory);
    }

} 
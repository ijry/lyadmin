<?php

namespace Docker\Tests\Context;

use Docker\Context\Context;
use Docker\Tests\TestCase;
use Symfony\Component\Process\Process;

class ContextTest extends TestCase
{
    public function testReturnsValidTarContent()
    {
        $directory = __DIR__.DIRECTORY_SEPARATOR."context-test";

        $context = new Context($directory);
        $process = new Process('/usr/bin/env tar c .', $directory);
        $process->run();

        $this->assertEquals(strlen($process->getOutput()), strlen($context->toTar()));
    }

    public function testReturnsValidTarStream()
    {
        $directory = __DIR__.DIRECTORY_SEPARATOR."context-test";

        $context = new Context($directory);
        $this->assertInternalType('resource', $context->toStream());
    }
}

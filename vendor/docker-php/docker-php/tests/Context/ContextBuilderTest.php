<?php

namespace Docker\Tests\Context;

use Docker\Context\ContextBuilder;
use Docker\Tests\TestCase;

class ContextBuilderTest extends TestCase
{
    public function testRemovesFilesOnDestruct()
    {
        $contextBuilder = new ContextBuilder();
        $context = $contextBuilder->getContext();

        $this->assertFileExists($context->getDirectory().'/Dockerfile');

        unset($contextBuilder);

        $this->assertFileNotExists($context->getDirectory().'/Dockerfile');
    }

    public function testWritesContextToDisk()
    {
        $contextBuilder = new ContextBuilder();
        $context = $contextBuilder->getContext();

        $this->assertFileExists($context->getDirectory().'/Dockerfile');
    }

    public function testHasDefaultFrom()
    {
        $contextBuilder = new ContextBuilder();
        $context = $contextBuilder->getContext();

        $this->assertStringEqualsFile($context->getDirectory().'/Dockerfile', 'FROM base');
    }

    public function testUsesCustomFrom()
    {
        $contextBuilder = new ContextBuilder();
        $contextBuilder->from('ubuntu:precise');

        $context = $contextBuilder->getContext();

        $this->assertStringEqualsFile($context->getDirectory().'/Dockerfile', 'FROM ubuntu:precise');
    }

    public function testCreatesTmpDirectory()
    {
        $contextBuilder = new ContextBuilder();
        $context = $contextBuilder->getContext();

        $this->assertFileExists($context->getDirectory());
    }

    public function testWriteTmpFiles()
    {
        $contextBuilder = new ContextBuilder();
        $contextBuilder->add('/foo', 'random content');

        $context  = $contextBuilder->getContext();
        $filename = preg_replace(<<<DOCKERFILE
#FROM base
ADD (.+?) /foo#
DOCKERFILE
            , "$1", $context->getDockerfileContent());

        $this->assertStringEqualsFile($context->getDirectory().'/'.$filename, 'random content');
    }

    public function testWritesAddCommands()
    {
        $contextBuilder = new ContextBuilder();
        $contextBuilder->add('/foo', 'foo file content');

        $context  = $contextBuilder->getContext();

        $this->assertRegExp(<<<DOCKERFILE
#FROM base
ADD .+? /foo#
DOCKERFILE
            , $context->getDockerfileContent()
        );
    }

    public function testWritesRunCommands()
    {
        $contextBuilder = new ContextBuilder();
        $contextBuilder->run('foo command');

        $context  = $contextBuilder->getContext();

        $this->assertStringEqualsFile($context->getDirectory().'/Dockerfile', <<<DOCKERFILE
FROM base
RUN foo command
DOCKERFILE
        );
    }

    public function testWritesEnvCommands()
    {
        $contextBuilder = new ContextBuilder();
        $contextBuilder->env('foo', 'bar');

        $context  = $contextBuilder->getContext();

        $this->assertStringEqualsFile($context->getDirectory().'/Dockerfile', <<<DOCKERFILE
FROM base
ENV foo bar
DOCKERFILE
        );
    }

    public function testWritesCopyCommands()
    {
        $contextBuilder = new ContextBuilder();
        $contextBuilder->copy('/foo', '/bar');

        $context  = $contextBuilder->getContext();

        $this->assertStringEqualsFile($context->getDirectory().'/Dockerfile', <<<DOCKERFILE
FROM base
COPY /foo /bar
DOCKERFILE
        );
    }

    public function testWritesWorkdirCommands()
    {
        $contextBuilder = new ContextBuilder();
        $contextBuilder->workdir('/foo');

        $context  = $contextBuilder->getContext();

        $this->assertStringEqualsFile($context->getDirectory().'/Dockerfile', <<<DOCKERFILE
FROM base
WORKDIR /foo
DOCKERFILE
        );
    }

    public function testWritesExposeCommands()
    {
        $contextBuilder = new ContextBuilder();
        $contextBuilder->expose('80');

        $context  = $contextBuilder->getContext();

        $this->assertStringEqualsFile($context->getDirectory().'/Dockerfile', <<<DOCKERFILE
FROM base
EXPOSE 80
DOCKERFILE
        );
    }
}

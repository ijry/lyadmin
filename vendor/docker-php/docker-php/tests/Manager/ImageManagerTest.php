<?php

namespace Docker\Tests\Manager;

use Docker\API\Model\AuthConfig;
use Docker\API\Model\BuildInfo;
use Docker\API\Model\CreateImageInfo;
use Docker\API\Model\PushImageInfo;
use Docker\Context\ContextBuilder;
use Docker\Manager\ImageManager;
use Docker\Tests\TestCase;

class ImageManagerTest extends TestCase
{
    /**
     * Return a container manager
     *
     * @return ImageManager
     */
    private function getManager()
    {
        return $this->getDocker()->getImageManager();
    }

    public function testBuildStream()
    {
        $contextBuilder = new ContextBuilder();
        $contextBuilder->from('ubuntu:precise');
        $contextBuilder->add('/test', 'test file content');

        $context = $contextBuilder->getContext();
        $buildStream = $this->getManager()->build($context->read(), ['t' => 'test-image'], ImageManager::FETCH_STREAM);

        $this->assertInstanceOf('Docker\Stream\BuildStream', $buildStream);

        $lastMessage = "";

        $buildStream->onFrame(function (BuildInfo $frame) use (&$lastMessage) {
            $lastMessage = $frame->getStream();
        });
        $buildStream->wait();

        $this->assertContains("Successfully built", $lastMessage);
    }

    public function testBuildObject()
    {
        $contextBuilder = new ContextBuilder();
        $contextBuilder->from('ubuntu:precise');
        $contextBuilder->add('/test', 'test file content');

        $context = $contextBuilder->getContext();
        $buildInfos = $this->getManager()->build($context->read(), ['t' => 'test-image'], ImageManager::FETCH_OBJECT);

        $this->assertInternalType('array', $buildInfos);
        $this->assertContains("Successfully built", $buildInfos[count($buildInfos) - 1]->getStream());
    }

    public function testCreateStream()
    {
        $createImageStream = $this->getManager()->create(null, [
            'fromImage' => 'registry:latest'
        ], ImageManager::FETCH_STREAM);

        $this->assertInstanceOf('Docker\Stream\CreateImageStream', $createImageStream);

        $firstMessage = null;

        $createImageStream->onFrame(function (CreateImageInfo $createImageInfo) use (&$firstMessage) {
            if (null === $firstMessage) {
                $firstMessage = $createImageInfo->getStatus();
            }
        });
        $createImageStream->wait();

        $this->assertContains("Pulling from library/registry", $firstMessage);
    }

    public function testCreateObject()
    {
        $createImagesInfos = $this->getManager()->create(null, [
            'fromImage' => 'registry:latest'
        ], ImageManager::FETCH_OBJECT);

        $this->assertInternalType('array', $createImagesInfos);
        $this->assertContains("Pulling from library/registry", $createImagesInfos[0]->getStatus());
    }

    public function testPushStream()
    {
        $contextBuilder = new ContextBuilder();
        $contextBuilder->from('ubuntu:precise');
        $contextBuilder->add('/test', 'test file content');

        $context = $contextBuilder->getContext();
        $this->getManager()->build($context->read(), ['t' => 'localhost:5000/test-image'], ImageManager::FETCH_OBJECT);

        $registryConfig = new AuthConfig();
        $registryConfig->setServeraddress('localhost:5000');
        $pushImageStream = $this->getManager()->push('localhost:5000/test-image', [
            'X-Registry-Auth' => $registryConfig
        ], ImageManager::FETCH_STREAM);

        $this->assertInstanceOf('Docker\Stream\PushStream', $pushImageStream);

        $firstMessage = null;

        $pushImageStream->onFrame(function (PushImageInfo $pushImageInfo) use (&$firstMessage) {
            if (null === $firstMessage) {
                $firstMessage = $pushImageInfo->getStatus();
            }
        });
        $pushImageStream->wait();

        $this->assertContains("The push refers to a repository [localhost:5000/test-image]", $firstMessage);
    }

    public function testPushObject()
    {
        $contextBuilder = new ContextBuilder();
        $contextBuilder->from('ubuntu:precise');
        $contextBuilder->add('/test', 'test file content');

        $context = $contextBuilder->getContext();
        $this->getManager()->build($context->read(), ['t' => 'localhost:5000/test-image'], ImageManager::FETCH_OBJECT);

        $registryConfig = new AuthConfig();
        $registryConfig->setServeraddress('localhost:5000');
        $pushImageInfos = $this->getManager()->push('localhost:5000/test-image', [
            'X-Registry-Auth' => $registryConfig
        ], ImageManager::FETCH_OBJECT);

        $this->assertInternalType('array', $pushImageInfos);
        $this->assertContains("The push refers to a repository [localhost:5000/test-image]", $pushImageInfos[0]->getStatus());
    }
}

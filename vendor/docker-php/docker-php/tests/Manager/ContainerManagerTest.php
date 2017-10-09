<?php

namespace Docker\Tests\Manager;

use Docker\API\Model\ContainerConfig;
use Docker\Manager\ContainerManager;
use Docker\Tests\TestCase;
use Http\Client\Plugin\Exception\ClientErrorException;

class ContainerManagerTest extends TestCase
{
    /**
     * Return the container manager
     *
     * @return ContainerManager
     */
    private function getManager()
    {
        return self::getDocker()->getContainerManager();
    }

    /**
     * Be sure to have image before doing test
     */
    public static function setUpBeforeClass()
    {
        self::getDocker()->getImageManager()->create(null, [
            'fromImage' => 'busybox:latest'
        ]);
    }

    public function testAttach()
    {
        $containerConfig = new ContainerConfig();
        $containerConfig->setImage('busybox:latest');
        $containerConfig->setCmd(['echo', '-n', 'output']);
        $containerConfig->setAttachStdout(true);
        $containerConfig->setLabels(new \ArrayObject(['docker-php-test' => 'true']));

        $containerCreateResult = $this->getManager()->create($containerConfig);
        $dockerRawStream = $this->getManager()->attach($containerCreateResult->getId(), [
            'stream' => true,
            'stdout' => true,
        ]);

        $stdoutFull = "";
        $dockerRawStream->onStdout(function ($stdout) use (&$stdoutFull) {
            $stdoutFull .= $stdout;
        });

        $this->getManager()->start($containerCreateResult->getId());
        $this->getManager()->wait($containerCreateResult->getId());

        $dockerRawStream->wait();

        $this->assertEquals("output", $stdoutFull);
    }

    public function testAttachWebsocket()
    {
        $containerConfig = new ContainerConfig();
        $containerConfig->setImage('busybox:latest');
        $containerConfig->setCmd(['sh']);
        $containerConfig->setAttachStdout(true);
        $containerConfig->setAttachStderr(true);
        $containerConfig->setAttachStdin(false);
        $containerConfig->setOpenStdin(true);
        $containerConfig->setTty(true);
        $containerConfig->setLabels(new \ArrayObject(['docker-php-test' => 'true']));

        $containerCreateResult = $this->getManager()->create($containerConfig);
        $webSocketStream       = $this->getManager()->attachWebsocket($containerCreateResult->getId(), [
            'stream' => true,
            'stdout' => true,
            'stderr' => true,
            'stdin'  => true,
        ]);

        $this->getManager()->start($containerCreateResult->getId());

        // Read the bash first line
        $webSocketStream->read();

        // No output after that so it should be false
        $this->assertFalse($webSocketStream->read());

        // Write something to the container
        $webSocketStream->write("echo test\n");

        // Test for echo present (stdin)
        $output = "";

        while (($data = $webSocketStream->read()) != false) {
            $output .= $data;
        }

        $this->assertContains("echo", $output);

        // Exit the container
        $webSocketStream->write("exit\n");
    }

    public function testLogs()
    {
        $containerConfig = new ContainerConfig();
        $containerConfig->setImage('busybox:latest');
        $containerConfig->setCmd(['echo', '-n', 'output']);
        $containerConfig->setAttachStdout(true);
        $containerConfig->setLabels(new \ArrayObject(['docker-php-test' => 'true']));

        $containerCreateResult = $this->getManager()->create($containerConfig);

        $this->getManager()->start($containerCreateResult->getId());
        $this->getManager()->wait($containerCreateResult->getId());

        $logs = $this->getManager()->logs($containerCreateResult->getId(), [
            'stdout' => true,
            'stderr' => true,
        ]);


        $this->assertContains("output", $logs['stdout'][0]);
    }
}

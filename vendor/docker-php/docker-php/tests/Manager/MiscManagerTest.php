<?php

namespace Docker\Tests\Manager;

use Docker\API\Model\Event;
use Docker\Manager\MiscManager;
use Docker\Tests\TestCase;

class MiscManagerTest extends TestCase
{
    /**
     * Return a container manager
     *
     * @return MiscManager
     */
    private function getManager()
    {
        return $this->getDocker()->getMiscManager();
    }

    public function testGetEventsStream()
    {
        $stream = $this->getManager()->getEvents([
            'since' => time() - 1,
            'until' => time() + 4
        ], MiscManager::FETCH_STREAM);
        $lastEvent = null;

        $stream->onFrame(function (Event $event) use (&$lastEvent) {
            $lastEvent = $event;
        });

        $this->getDocker()->getImageManager()->create(null, [
            'fromImage' => 'busybox:latest'
        ]);
        $stream->wait();

        $this->assertInstanceOf('Docker\API\Model\Event', $lastEvent);
    }

    public function testGetEventsObject()
    {
        $events = $this->getManager()->getEvents([
            'since' => time() - (60 * 60 * 24),
            'until' => time()
        ], MiscManager::FETCH_OBJECT);

        $this->assertInternalType('array', $events);
        $this->assertInstanceOf('Docker\API\Model\Event', $events[0]);
    }
}

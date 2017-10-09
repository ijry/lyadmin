<?php

namespace Docker\Manager;

use Docker\API\Model\Event;
use Docker\API\Resource\MiscResource;
use Docker\Stream\EventStream;

class MiscManager extends MiscResource
{
    const FETCH_STREAM = 'stream';

    /**
     * {@inheritdoc}
     */
    public function getEvents($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $response = parent::getEvents($parameters, self::FETCH_RESPONSE);

        if (200 === $response->getStatusCode()) {
            if (self::FETCH_STREAM === $fetch) {
                return new EventStream($response->getBody(), $this->serializer);
            }

            if (self::FETCH_OBJECT === $fetch) {
                $eventList = [];

                $stream = new EventStream($response->getBody(), $this->serializer);
                $stream->onFrame(function (Event $event) use (&$eventList) {
                    $eventList[] = $event;
                });
                $stream->wait();

                return $eventList;
            }
        }

        return $response;
    }
}

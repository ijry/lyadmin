<?php

namespace Docker\Manager;

use Docker\API\Resource\ExecResource;
use Docker\Stream\DockerRawStream;

class ExecManager extends ExecResource
{
    const FETCH_STREAM = 'stream';

    public function start($id, \Docker\API\Model\ExecStartConfig $execStartConfig, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $response = parent::start($id, $execStartConfig, $parameters, $fetch);

        if ($response->getStatusCode() == 200 && DockerRawStream::HEADER == $response->getHeaderLine('Content-Type')) {
            if ($fetch == self::FETCH_STREAM) {
                return new DockerRawStream($response->getBody());
            }
        }

        return $response;
    }

}

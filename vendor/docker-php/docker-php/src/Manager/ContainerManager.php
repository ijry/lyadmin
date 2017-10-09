<?php

namespace Docker\Manager;

use Docker\API\Resource\ContainerResource;
use Docker\Stream\AttachWebsocketStream;
use Docker\Stream\DockerRawStaticStream;
use Docker\Stream\DockerRawStream;
use Joli\Jane\OpenApi\Client\QueryParam;

class ContainerManager extends ContainerResource
{
    const FETCH_STREAM = 'stream';

    /**
     * {@inheritdoc}
     *
     * @return \Psr\Http\Message\ResponseInterface|DockerRawStream
     */
    public function attach($id, $parameters = [], $fetch = self::FETCH_STREAM)
    {
        $response = parent::attach($id, $parameters, $fetch);

        if ($response->getStatusCode() == 200 && DockerRawStream::HEADER == $response->getHeaderLine('Content-Type')) {
            if ($fetch == self::FETCH_STREAM) {
                return new DockerRawStream($response->getBody());
            }
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Psr\Http\Message\ResponseInterface|AttachWebsocketStream
     */
    public function attachWebsocket($id, $parameters = [], $fetch = self::FETCH_STREAM)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('logs', null);
        $queryParam->setDefault('stream', null);
        $queryParam->setDefault('stdin', null);
        $queryParam->setDefault('stdout', null);
        $queryParam->setDefault('stderr', null);

        $url      = '/containers/{id}/attach/ws';
        $url      = str_replace('{id}', $id, $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));

        $headers  = array_merge([
            'Host' => 'localhost',
            'Origin' => 'php://docker-php',
            'Upgrade' => 'websocket',
            'Connection' => 'Upgrade',
            'Sec-WebSocket-Version' => '13',
            'Sec-WebSocket-Key' => base64_encode(uniqid()),
        ], $queryParam->buildHeaders($parameters));

        $body     = $queryParam->buildFormDataString($parameters);

        $request  = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() == 101) {
            if ($fetch == self::FETCH_STREAM) {
                return new AttachWebsocketStream($response->getBody());
            }
        }

        return $response;
    }

    /**
     * @inheritDoc
     *
     * @return \Psr\Http\Message\ResponseInterface|DockerRawStream|string[][]
     */
    public function logs($id, $parameters = [], $fetch = self::FETCH_OBJECT) {
        $response = parent::logs($id, $parameters, $fetch);

        if ($response->getStatusCode() == 200) {
            if ($fetch == self::FETCH_STREAM) {
                return new DockerRawStream($response->getBody());
            }

            if ($fetch == self::FETCH_OBJECT) {
                $dockerRawStream = new DockerRawStream($response->getBody());

                $logs = [
                    'stdout' => [],
                    'stderr' => []
                ];

                $dockerRawStream->onStdout(function ($logLine) use (&$logs) {
                    $logs['stdout'][] = $logLine;
                });
                $dockerRawStream->onStderr(function ($logLine) use (&$logs) {
                    $logs['stderr'][] = $logLine;
                });

                $dockerRawStream->wait();

                return $logs;
            }
        }

        return $response;
    }
}

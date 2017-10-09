<?php

namespace Docker\Manager;

use Docker\API\Model\AuthConfig;
use Docker\API\Model\BuildInfo;
use Docker\API\Model\CreateImageInfo;
use Docker\API\Model\PushImageInfo;
use Docker\API\Resource\ImageResource;
use Docker\Stream\BuildStream;
use Docker\Stream\CreateImageStream;
use Docker\Stream\PushStream;
use Docker\Stream\TarStream;
use Joli\Jane\OpenApi\Client\QueryParam;
use Psr\Http\Message\StreamInterface;

class ImageManager extends ImageResource
{
    const FETCH_STREAM = 'stream';

    /**
     * {@inheritdoc}
     *
     * @param resource|StreamInterface|string $inputStream The input stream (encoded with tar) containing the Dockerfile
     *                                                     and other files for the image.
     *
     * @return \Psr\Http\Message\ResponseInterface|BuildInfo[]|BuildStream
     */
    public function build($inputStream, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        if (is_resource($inputStream)) {
            $inputStream = new TarStream($inputStream);
        }

        $response = parent::build($inputStream, $parameters, $fetch);

        if (200 === $response->getStatusCode()) {
            if (self::FETCH_STREAM === $fetch) {
                return new BuildStream($response->getBody(), $this->serializer);
            }

            if (self::FETCH_OBJECT === $fetch) {
                $buildInfoList = [];

                $stream = new BuildStream($response->getBody(), $this->serializer);
                $stream->onFrame(function (BuildInfo $buildInfo) use (&$buildInfoList) {
                    $buildInfoList[] = $buildInfo;
                });
                $stream->wait();

                return $buildInfoList;
            }
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Psr\Http\Message\ResponseInterface|CreateImageInfo[]|CreateImageStream
     */
    public function create($inputStream = null, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        if (isset($parameters['X-Registry-Auth']) && $parameters['X-Registry-Auth'] instanceof AuthConfig) {
            $parameters['X-Registry-Auth'] = base64_encode($this->serializer->serialize($parameters['X-Registry-Auth'], 'json'));
        }

        $response = parent::create($inputStream, $parameters, self::FETCH_RESPONSE);

        if (200 === $response->getStatusCode()) {
            if (self::FETCH_STREAM === $fetch) {
                return new CreateImageStream($response->getBody(), $this->serializer);
            }

            if (self::FETCH_OBJECT === $fetch) {
                $createImageInfoList = [];

                $stream = new CreateImageStream($response->getBody(), $this->serializer);
                $stream->onFrame(function (CreateImageInfo $createImageInfo) use (&$createImageInfoList) {
                    $createImageInfoList[] = $createImageInfo;
                });
                $stream->wait();

                return $createImageInfoList;
            }
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Psr\Http\Message\ResponseInterface|PushImageInfo[]|CreateImageStream
     */
    public function push($name, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        if (isset($parameters['X-Registry-Auth']) && $parameters['X-Registry-Auth'] instanceof AuthConfig) {
            $parameters['X-Registry-Auth'] = base64_encode($this->serializer->serialize($parameters['X-Registry-Auth'], 'json'));
        }

        $queryParam = new QueryParam();
        $queryParam->setDefault('tag', null);
        $queryParam->setDefault('X-Registry-Auth', null);
        $queryParam->setHeaderParameters(['X-Registry-Auth']);

        $url      = 'http://localhost/images/{name}/push';
        $url      = str_replace('{name}', $name, $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));

        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));

        $body     = $queryParam->buildFormDataString($parameters);

        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        if (200 === $response->getStatusCode()) {
            if (self::FETCH_STREAM === $fetch) {
                return new PushStream($response->getBody(), $this->serializer);
            }

            if (self::FETCH_OBJECT === $fetch) {
                $pushImageInfoList = [];

                $stream = new PushStream($response->getBody(), $this->serializer);
                $stream->onFrame(function (PushImageInfo $pushImageInfo) use (&$pushImageInfoList) {
                    $pushImageInfoList[] = $pushImageInfo;
                });
                $stream->wait();

                return $pushImageInfoList;
            }
        }

        return $response;
    }
}

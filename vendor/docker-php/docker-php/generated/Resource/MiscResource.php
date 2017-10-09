<?php

namespace Docker\API\Resource;

use Joli\Jane\OpenApi\Client\QueryParam;
use Joli\Jane\OpenApi\Client\Resource;

class MiscResource extends Resource
{
    /**
     * Check auth configuration.
     *
     * @param \Docker\API\Model\AuthConfig $authConfig Authentication to check
     * @param array                        $parameters List of parameters
     * @param string                       $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\AuthResult
     */
    public function checkAuthentication(\Docker\API\Model\AuthConfig $authConfig, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/auth';
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $this->serializer->serialize($authConfig, 'json');
        $request    = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\AuthResult', 'json');
            }
        }

        return $response;
    }

    /**
     * Display system-wide information.
     *
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\SystemInformation
     */
    public function getSystemInformation($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/info';
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\SystemInformation', 'json');
            }
        }

        return $response;
    }

    /**
     * Show the docker version information.
     *
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\Version
     */
    public function getVersion($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/version';
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\Version', 'json');
            }
        }

        return $response;
    }

    /**
     * Ping the docker server.
     *
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function ping($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/_ping';
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Get container events from docker, either in real time via streaming, or via polling (using since).
     *
     * @param array $parameters {
     *
     *     @var int $since Timestamp used for polling
     *     @var int $until Timestamp used for polling
     *     @var string $filters A json encoded value of the filters (a map[string][]string) to process on the event list.
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getEvents($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('since', null);
        $queryParam->setDefault('until', null);
        $queryParam->setDefault('filters', null);
        $url      = '/events';
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }
}

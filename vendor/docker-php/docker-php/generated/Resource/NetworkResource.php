<?php

namespace Docker\API\Resource;

use Joli\Jane\OpenApi\Client\QueryParam;
use Joli\Jane\OpenApi\Client\Resource;

class NetworkResource extends Resource
{
    /**
     * List networks.
     *
     * @param array $parameters {
     *
     *     @var string $filters JSON encoded value of the filters (a map[string][]string) to process on the networks list.
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\Network[]
     */
    public function findAll($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('filters', null);
        $url      = '/networks';
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\Network[]', 'json');
            }
        }

        return $response;
    }

    /**
     * Remove a network.
     *
     * @param string $id         Network id or name
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function remove($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/networks/{id}';
        $url        = str_replace('{id}', urlencode($id), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('DELETE', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Inspect network.
     *
     * @param string $id         Network id or name
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\Network
     */
    public function find($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/networks/{id}';
        $url        = str_replace('{id}', urlencode($id), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\Network', 'json');
            }
        }

        return $response;
    }

    /**
     * Create network.
     *
     * @param \Docker\API\Model\NetworkCreateConfig $networkConfig Network configuration
     * @param array                                 $parameters    {
     *
     *     @var string $Content-Type Content Type of input
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\NetworkCreateResult
     */
    public function create(\Docker\API\Model\NetworkCreateConfig $networkConfig, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('Content-Type', 'application/json');
        $queryParam->setHeaderParameters(['Content-Type']);
        $url      = '/networks/create';
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $this->serializer->serialize($networkConfig, 'json');
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('201' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\NetworkCreateResult', 'json');
            }
        }

        return $response;
    }

    /**
     * Connect a container to a network.
     *
     * @param string                             $id         Network id or name
     * @param \Docker\API\Model\ContainerConnect $container  Container
     * @param array                              $parameters {
     *
     *     @var string $Content-Type Content Type of input
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function connect($id, \Docker\API\Model\ContainerConnect $container, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('Content-Type', 'application/json');
        $queryParam->setHeaderParameters(['Content-Type']);
        $url      = '/networks/{id}/connect';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $this->serializer->serialize($container, 'json');
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Disconnect a container to a network.
     *
     * @param string                                $id         Network id or name
     * @param \Docker\API\Model\ContainerDisconnect $container  Container
     * @param array                                 $parameters {
     *
     *     @var string $Content-Type Content Type of input
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function disconnect($id, \Docker\API\Model\ContainerDisconnect $container, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('Content-Type', 'application/json');
        $queryParam->setHeaderParameters(['Content-Type']);
        $url      = '/networks/{id}/disconnect';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $this->serializer->serialize($container, 'json');
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }
}

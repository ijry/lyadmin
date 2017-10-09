<?php

namespace Docker\API\Resource;

use Joli\Jane\OpenApi\Client\QueryParam;
use Joli\Jane\OpenApi\Client\Resource;

class VolumeResource extends Resource
{
    /**
     * List volumes.
     *
     * @param array $parameters {
     *
     *     @var string $filters JSON encoded value of the filters (a map[string][]string) to process on the volumes list
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\VolumeList
     */
    public function findAll($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('filters', null);
        $url      = '/volumes';
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\VolumeList', 'json');
            }
        }

        return $response;
    }

    /**
     * Create a volume.
     *
     * @param \Docker\API\Model\VolumeConfig $volumeConfig Volume configuration
     * @param array                          $parameters   {
     *
     *     @var string $Content-Type Content Type of input
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\Volume
     */
    public function create(\Docker\API\Model\VolumeConfig $volumeConfig, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('Content-Type', 'application/json');
        $queryParam->setHeaderParameters(['Content-Type']);
        $url      = '/volumes/create';
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $this->serializer->serialize($volumeConfig, 'json');
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('201' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\Volume', 'json');
            }
        }

        return $response;
    }

    /**
     * Instruct the driver to remove the volume.
     *
     * @param string $name       Volume name or id
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function remove($name, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/volumes/{name}';
        $url        = str_replace('{name}', urlencode($name), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('DELETE', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Inspect a volume.
     *
     * @param string $name       Volume name or id
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\Volume
     */
    public function find($name, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/volumes/{name}';
        $url        = str_replace('{name}', urlencode($name), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\Volume', 'json');
            }
        }

        return $response;
    }
}

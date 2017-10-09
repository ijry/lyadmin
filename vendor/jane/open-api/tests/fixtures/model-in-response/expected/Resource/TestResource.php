<?php

namespace Joli\Jane\OpenApi\Tests\Expected\Resource;

use Joli\Jane\OpenApi\Runtime\Client\QueryParam;
use Joli\Jane\OpenApi\Runtime\Client\Resource;

class TestResource extends Resource
{
    /**
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Joli\Jane\OpenApi\Tests\Expected\Model\Schema|\Joli\Jane\OpenApi\Tests\Expected\Model\Error
     */
    public function getTest($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/test';
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $promise    = $this->httpClient->sendAsyncRequest($request);
        if (self::FETCH_PROMISE === $fetch) {
            return $promise;
        }
        $response = $promise->wait();
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Joli\\Jane\\OpenApi\\Tests\\Expected\\Model\\Schema', 'json');
            }
            if ('400' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Joli\\Jane\\OpenApi\\Tests\\Expected\\Model\\Error', 'json');
            }
            if ('404' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Joli\\Jane\\OpenApi\\Tests\\Expected\\Model\\Error', 'json');
            }
        }

        return $response;
    }

    /**
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Joli\Jane\OpenApi\Tests\Expected\Model\Schema[]
     */
    public function getTestList($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/test-list';
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $promise    = $this->httpClient->sendAsyncRequest($request);
        if (self::FETCH_PROMISE === $fetch) {
            return $promise;
        }
        $response = $promise->wait();
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Joli\\Jane\\OpenApi\\Tests\\Expected\\Model\\Schema[]', 'json');
            }
        }

        return $response;
    }
}

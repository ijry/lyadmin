<?php

namespace Joli\Jane\OpenApi\Tests\Expected\Resource;

use Joli\Jane\OpenApi\Runtime\Client\QueryParam;
use Joli\Jane\OpenApi\Runtime\Client\Resource;

class TestResource extends Resource
{
    /**
     * @param string $testString
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function testSimpleBodyParameter($testString, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/test-simple';
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $testString;
        $request    = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $promise    = $this->httpClient->sendAsyncRequest($request);
        if (self::FETCH_PROMISE === $fetch) {
            return $promise;
        }
        $response = $promise->wait();

        return $response;
    }

    /**
     * @param \Joli\Jane\OpenApi\Tests\Expected\Model\Schema $testObject
     * @param array                                          $parameters List of parameters
     * @param string                                         $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function testObjectBodyParameter(\Joli\Jane\OpenApi\Tests\Expected\Model\Schema $testObject, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/test-object';
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $this->serializer->serialize($testObject, 'json');
        $request    = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $promise    = $this->httpClient->sendAsyncRequest($request);
        if (self::FETCH_PROMISE === $fetch) {
            return $promise;
        }
        $response = $promise->wait();

        return $response;
    }

    /**
     * @param array  $testObjectList
     * @param array  $parameters     List of parameters
     * @param string $fetch          Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function testObjectListBodyParameter(array $testObjectList, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/test-object-list';
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $testObjectList;
        $request    = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $promise    = $this->httpClient->sendAsyncRequest($request);
        if (self::FETCH_PROMISE === $fetch) {
            return $promise;
        }
        $response = $promise->wait();

        return $response;
    }
}

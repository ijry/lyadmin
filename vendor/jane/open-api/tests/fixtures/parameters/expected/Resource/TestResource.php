<?php

namespace Joli\Jane\OpenApi\Tests\Expected\Resource;

use Joli\Jane\OpenApi\Runtime\Client\QueryParam;
use Joli\Jane\OpenApi\Runtime\Client\Resource;

class TestResource extends Resource
{
    /**
     * @param array $parameters {
     *
     *     @var string $testString
     *     @var int $testInteger
     *     @var float $testFloat
     *     @var array $testArray
     *     @var string $testRequired
     *     @var string $testDefault
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function testQueryParameters($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('testString', null);
        $queryParam->setDefault('testInteger', null);
        $queryParam->setDefault('testFloat', null);
        $queryParam->setDefault('testArray', null);
        $queryParam->setRequired('testRequired');
        $queryParam->setDefault('testDefault', 'test');
        $url     = '/test-query';
        $url     = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body    = $queryParam->buildFormDataString($parameters);
        $request = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $promise = $this->httpClient->sendAsyncRequest($request);
        if (self::FETCH_PROMISE === $fetch) {
            return $promise;
        }
        $response = $promise->wait();

        return $response;
    }

    /**
     * @param array $parameters {
     *
     *     @var string $testString
     *     @var int $testInteger
     *     @var float $testFloat
     *     @var array $testArray
     *     @var string $testRequired
     *     @var string $testDefault
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function testHeaderParameters($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('testString', null);
        $queryParam->setHeaderParameters(['testString']);
        $queryParam->setDefault('testInteger', null);
        $queryParam->setHeaderParameters(['testInteger']);
        $queryParam->setDefault('testFloat', null);
        $queryParam->setHeaderParameters(['testFloat']);
        $queryParam->setDefault('testArray', null);
        $queryParam->setHeaderParameters(['testArray']);
        $queryParam->setRequired('testRequired');
        $queryParam->setHeaderParameters(['testRequired']);
        $queryParam->setDefault('testDefault', 'test');
        $queryParam->setHeaderParameters(['testDefault']);
        $url     = '/test-header';
        $url     = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body    = $queryParam->buildFormDataString($parameters);
        $request = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $promise = $this->httpClient->sendAsyncRequest($request);
        if (self::FETCH_PROMISE === $fetch) {
            return $promise;
        }
        $response = $promise->wait();

        return $response;
    }

    /**
     * @param array $parameters {
     *
     *     @var string $testString
     *     @var int $testInteger
     *     @var float $testFloat
     *     @var array $testArray
     *     @var string $testRequired
     *     @var string $testDefault
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function testFormParameters($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('testString', null);
        $queryParam->setFormParameters(['testString']);
        $queryParam->setDefault('testInteger', null);
        $queryParam->setFormParameters(['testInteger']);
        $queryParam->setDefault('testFloat', null);
        $queryParam->setFormParameters(['testFloat']);
        $queryParam->setDefault('testArray', null);
        $queryParam->setFormParameters(['testArray']);
        $queryParam->setRequired('testRequired');
        $queryParam->setFormParameters(['testRequired']);
        $queryParam->setDefault('testDefault', 'test');
        $queryParam->setFormParameters(['testDefault']);
        $url     = '/test-form';
        $url     = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body    = $queryParam->buildFormDataString($parameters);
        $request = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $promise = $this->httpClient->sendAsyncRequest($request);
        if (self::FETCH_PROMISE === $fetch) {
            return $promise;
        }
        $response = $promise->wait();

        return $response;
    }

    /**
     * @param string $testString
     * @param int    $testInteger
     * @param float  $testFloat
     * @param array  $parameters  List of parameters
     * @param string $fetch       Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function testPathParameters($testString, $testInteger, $testFloat, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/test-path/{testString}/{testInteger}/{testFloat}';
        $url        = str_replace('{testString}', urlencode($testString), $url);
        $url        = str_replace('{testInteger}', urlencode($testInteger), $url);
        $url        = str_replace('{testFloat}', urlencode($testFloat), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $promise    = $this->httpClient->sendAsyncRequest($request);
        if (self::FETCH_PROMISE === $fetch) {
            return $promise;
        }
        $response = $promise->wait();

        return $response;
    }

    /**
     * @param int    $testInteger
     * @param array  $parameters  List of parameters
     * @param string $fetch       Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getByTestInteger($testInteger, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/{test_integer}';
        $url        = str_replace('{test_integer}', urlencode($testInteger), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $promise    = $this->httpClient->sendAsyncRequest($request);
        if (self::FETCH_PROMISE === $fetch) {
            return $promise;
        }
        $response = $promise->wait();

        return $response;
    }
}

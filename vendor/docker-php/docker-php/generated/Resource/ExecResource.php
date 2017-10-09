<?php

namespace Docker\API\Resource;

use Joli\Jane\OpenApi\Client\QueryParam;
use Joli\Jane\OpenApi\Client\Resource;

class ExecResource extends Resource
{
    /**
     * Sets up an exec instance in a running container id.
     *
     * @param string                       $id         Container name or id
     * @param \Docker\API\Model\ExecConfig $execConfig Exec configuration
     * @param array                        $parameters {
     *
     *     @var string $Content-Type Content Type Header
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\ExecCreateResult
     */
    public function create($id, \Docker\API\Model\ExecConfig $execConfig, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('Content-Type', 'application/json');
        $queryParam->setHeaderParameters(['Content-Type']);
        $url      = '/containers/{id}/exec';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $this->serializer->serialize($execConfig, 'json');
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('201' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\ExecCreateResult', 'json');
            }
        }

        return $response;
    }

    /**
     * Starts a previously set up exec instance id. If detach is true, this API returns after starting the exec command. Otherwise, this API sets up an interactive session with the exec command.
     *
     * @param string                            $id              Exec instance id
     * @param \Docker\API\Model\ExecStartConfig $execStartConfig Exec configuration
     * @param array                             $parameters      {
     *
     *     @var string $Content-Type Content Type Header
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function start($id, \Docker\API\Model\ExecStartConfig $execStartConfig, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('Content-Type', 'application/json');
        $queryParam->setHeaderParameters(['Content-Type']);
        $url      = '/exec/{id}/start';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $this->serializer->serialize($execStartConfig, 'json');
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Resize the tty session used by the exec command id.
     *
     * @param string $id         Exec instance id
     * @param array  $parameters {
     *
     *     @var int $h Height of the tty session
     *     @var int $w Width of the tty session
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function resize($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('h', null);
        $queryParam->setDefault('w', null);
        $url      = '/exec/{id}/resize';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Return low-level information about the exec command id.
     *
     * @param string $id         Exec instance id
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\ExecCommand
     */
    public function find($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/exec/{id}/json';
        $url        = str_replace('{id}', urlencode($id), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\ExecCommand', 'json');
            }
        }

        return $response;
    }
}

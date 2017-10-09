<?php

namespace Docker\API\Resource;

use Joli\Jane\OpenApi\Client\QueryParam;
use Joli\Jane\OpenApi\Client\Resource;

class ContainerResource extends Resource
{
    /**
     * List containers.
     *
     * @param array $parameters {
     *
     *     @var bool $all Show all containers. Only running containers are shown by default (i.e., this defaults to false)
     *     @var int $limit Show <limit> last created containers, include non-running ones.
     *     @var string $since Show only containers created since Id, include non-running ones.
     *     @var string $before Show only containers created before Id, include non-running ones.
     *     @var bool $size 1/True/true or 0/False/false, Show the containers sizes.
     *     @var array $filters A JSON encoded value of the filters (a map[string][]string) to process on the containers list
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\ContainerInfo[]
     */
    public function findAll($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('all', false);
        $queryParam->setDefault('limit', null);
        $queryParam->setDefault('since', null);
        $queryParam->setDefault('before', null);
        $queryParam->setDefault('size', null);
        $queryParam->setDefault('filters', null);
        $url      = '/containers/json';
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\ContainerInfo[]', 'json');
            }
        }

        return $response;
    }

    /**
     * Create a container.
     *
     * @param \Docker\API\Model\ContainerConfig $container  Container to create
     * @param array                             $parameters {
     *
     *     @var string $name Assign the specified name to the container. Must match /?[a-zA-Z0-9_-]+.
     *     @var string $Content-Type Content Type of input
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\ContainerCreateResult
     */
    public function create(\Docker\API\Model\ContainerConfig $container, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('name', null);
        $queryParam->setDefault('Content-Type', 'application/json');
        $queryParam->setHeaderParameters(['Content-Type']);
        $url      = '/containers/create';
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $this->serializer->serialize($container, 'json');
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('201' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\ContainerCreateResult', 'json');
            }
        }

        return $response;
    }

    /**
     * Return low-level information on the container id.
     *
     * @param string $id         The container id or name
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\Container
     */
    public function find($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/containers/{id}/json';
        $url        = str_replace('{id}', urlencode($id), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\Container', 'json');
            }
        }

        return $response;
    }

    /**
     * List processes running inside the container id.
     *
     * @param string $id         The container id or name
     * @param array  $parameters {
     *
     *     @var string $ps_args ps arguments to use (e.g., aux)
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\ContainerTop
     */
    public function listProcesses($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('ps_args', null);
        $url      = '/containers/{id}/top';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\ContainerTop', 'json');
            }
        }

        return $response;
    }

    /**
     * Get stdout and stderr logs from the container id. Note: This endpoint works only for containers with json-file logging driver.
     *
     * @param string $id         The container id or name
     * @param array  $parameters {
     *
     *     @var bool $follow 1/True/true or 0/False/false, return stream. Default false.
     *     @var bool $stdout 1/True/true or 0/False/false, show stdout log. Default false.
     *     @var bool $stderr 1/True/true or 0/False/false, show stderr log. Default false.
     *     @var int $since UNIX timestamp (integer) to filter logs. Specifying a timestamp will only output log-entries since that timestamp. Default: 0 (unfiltered)
     *     @var bool $timestamps 1/True/true or 0/False/false, print timestamps for every log line.
     *     @var string $tail Output specified number of lines at the end of logs: all or <number>. Default all.
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function logs($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('follow', false);
        $queryParam->setDefault('stdout', false);
        $queryParam->setDefault('stderr', false);
        $queryParam->setDefault('since', 0);
        $queryParam->setDefault('timestamps', false);
        $queryParam->setDefault('tail', null);
        $url      = '/containers/{id}/logs';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Inspect changes on a container’s filesystem.
     *
     * @param string $id         The container id or name
     * @param array  $parameters {
     *
     *     @var int $kind Kind of changes
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\ContainerChange[]
     */
    public function changes($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('kind', null);
        $url      = '/containers/{id}/changes';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\ContainerChange[]', 'json');
            }
        }

        return $response;
    }

    /**
     * Export the contents of container id.
     *
     * @param string $id         The container id or name
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function export($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/containers/{id}/export';
        $url        = str_replace('{id}', urlencode($id), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * This endpoint returns a live stream of a container’s resource usage statistics.
     *
     * @param string $id         The container id or name
     * @param array  $parameters {
     *
     *     @var bool $stream Stream stats
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function stats($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('stream', null);
        $url      = '/containers/{id}/stats';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Resize the TTY for container with id. The unit is number of characters. You must restart the container for the resize to take effect.
     *
     * @param string $id         The container id or name
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
        $url      = '/containers/{id}/resize';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Start the container id.
     *
     * @param string $id         The container id or name
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function start($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/containers/{id}/start';
        $url        = str_replace('{id}', urlencode($id), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Stop the container id.
     *
     * @param string $id         The container id or name
     * @param array  $parameters {
     *
     *     @var int $t number of seconds to wait before killing the container
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function stop($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('t', null);
        $url      = '/containers/{id}/stop';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Restart the container id.
     *
     * @param string $id         The container id or name
     * @param array  $parameters {
     *
     *     @var int $t number of seconds to wait before killing the container
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function restart($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('t', null);
        $url      = '/containers/{id}/restart';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Send a posix signal to a container.
     *
     * @param string $id         The container id or name
     * @param array  $parameters {
     *
     *     @var string $signal Signal to send to the container, integer or string like SIGINT, defaults to SIGKILL
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function kill($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('signal', null);
        $url      = '/containers/{id}/kill';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Update resource configs of one or more containers.
     *
     * @param string                           $id             The container id or name
     * @param \Docker\API\Model\ResourceUpdate $resourceConfig Resources to update on container
     * @param array                            $parameters     List of parameters
     * @param string                           $fetch          Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\ContainerUpdateResult
     */
    public function update($id, \Docker\API\Model\ResourceUpdate $resourceConfig, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/containers/{id}/update';
        $url        = str_replace('{id}', urlencode($id), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $this->serializer->serialize($resourceConfig, 'json');
        $request    = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\ContainerUpdateResult', 'json');
            }
        }

        return $response;
    }

    /**
     * Rename the container id to a new_name.
     *
     * @param string $id         The container id or name
     * @param array  $parameters {
     *
     *     @var string $name New name for the container
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function rename($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setRequired('name');
        $url      = '/containers/{id}/rename';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Pause the container id.
     *
     * @param string $id         The container id or name
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function pause($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/containers/{id}/pause';
        $url        = str_replace('{id}', urlencode($id), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Unpause the container id.
     *
     * @param string $id         The container id or name
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function unpause($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/containers/{id}/unpause';
        $url        = str_replace('{id}', urlencode($id), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Attach to the container id.
     *
     * @param string $id         The container id or name
     * @param array  $parameters {
     *
     *     @var string $logs 1/True/true or 0/False/false, return logs. Default false
     *     @var string $stream 1/True/true or 0/False/false, return stream. Default false
     *     @var string $stdin 1/True/true or 0/False/false, if stream=true, attach to stdin. Default false.
     *     @var string $stdout 1/True/true or 0/False/false, if logs=true, return stdout log, if stream=true, attach to stdout. Default false.
     *     @var string $stderr 1/True/true or 0/False/false, if logs=true, return stderr log, if stream=true, attach to stderr. Default false.
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function attach($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('logs', null);
        $queryParam->setDefault('stream', null);
        $queryParam->setDefault('stdin', null);
        $queryParam->setDefault('stdout', null);
        $queryParam->setDefault('stderr', null);
        $url      = '/containers/{id}/attach';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Attach to the container id with a websocket.
     *
     * @param string $id         The container id or name
     * @param array  $parameters {
     *
     *     @var string $logs 1/True/true or 0/False/false, return logs. Default false
     *     @var string $stream 1/True/true or 0/False/false, return stream. Default false
     *     @var string $stdin 1/True/true or 0/False/false, if stream=true, attach to stdin. Default false.
     *     @var string $stdout 1/True/true or 0/False/false, if logs=true, return stdout log, if stream=true, attach to stdout. Default false.
     *     @var string $stderr 1/True/true or 0/False/false, if logs=true, return stderr log, if stream=true, attach to stderr. Default false.
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function attachWebsocket($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('logs', null);
        $queryParam->setDefault('stream', null);
        $queryParam->setDefault('stdin', null);
        $queryParam->setDefault('stdout', null);
        $queryParam->setDefault('stderr', null);
        $url      = '/containers/{id}/attach/ws';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Block until container id stops, then returns the exit code.
     *
     * @param string $id         The container id or name
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\ContainerWait
     */
    public function wait($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/containers/{id}/wait';
        $url        = str_replace('{id}', urlencode($id), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\ContainerWait', 'json');
            }
        }

        return $response;
    }

    /**
     * Remove the container id from the filesystem.
     *
     * @param string $id         The container id or name
     * @param array  $parameters {
     *
     *     @var string $v 1/True/true or 0/False/false, Remove the volumes associated to the container. Default false.
     *     @var string $force 1/True/true or 0/False/false, Kill then remove the container. Default false.
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function remove($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('v', null);
        $queryParam->setDefault('force', null);
        $url      = '/containers/{id}';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('DELETE', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Get an tar archive of a resource in the filesystem of container id.
     *
     * @param string $id         The container id or name
     * @param array  $parameters {
     *
     *     @var string $path Resource in the container’s filesystem to archive.
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getArchive($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setRequired('path');
        $url      = '/containers/{id}/archive';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Retrieving information about files and folders in a container.
     *
     * @param string $id         The container id or name
     * @param array  $parameters {
     *
     *     @var string $path Resource in the container’s filesystem to archive.
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getArchiveInformation($id, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setRequired('path');
        $url      = '/containers/{id}/archive';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('HEAD', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Upload a tar archive to be extracted to a path in the filesystem of container id.
     *
     * @param string $id          The container id or name
     * @param string $inputStream The input stream must be a tar archive compressed with one of the following algorithms: identity (no compression), gzip, bzip2, xz.
     * @param array  $parameters  {
     *
     *     @var string $path Path to a directory in the container to extract the archive’s contents into.
     *     @var string $noOverwriteDirNonDir If “1”, “true”, or “True” then it will be an error if unpacking the given content would cause an existing directory to be replaced with a non-directory and vice versa.
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function putArchive($id, $inputStream, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setRequired('path');
        $queryParam->setDefault('noOverwriteDirNonDir', null);
        $url      = '/containers/{id}/archive';
        $url      = str_replace('{id}', urlencode($id), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $inputStream;
        $request  = $this->messageFactory->createRequest('PUT', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }
}

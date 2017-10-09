<?php

namespace Docker\API\Resource;

use Joli\Jane\OpenApi\Client\QueryParam;
use Joli\Jane\OpenApi\Client\Resource;

class ImageResource extends Resource
{
    /**
     * List Images.
     *
     * @param array $parameters {
     *
     *     @var bool $all Show all images. Only images from a final layer (no children) are shown by default.
     *     @var string $filters A JSON encoded value of the filters (a map[string][]string) to process on the containers list
     *     @var string $filter Only return images with the specified name.
     *     @var bool $digests Show digest information, default to false
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\ImageItem[]
     */
    public function findAll($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('all', false);
        $queryParam->setDefault('filters', null);
        $queryParam->setDefault('filter', null);
        $queryParam->setDefault('digests', null);
        $url      = '/images/json';
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\ImageItem[]', 'json');
            }
        }

        return $response;
    }

    /**
     * Build an image from Dockerfile via stdin.
     *
     * @param string $inputStream The input stream must be a tar archive compressed with one of the following algorithms: identity (no compression), gzip, bzip2, xz.
     * @param array  $parameters  {
     *
     *     @var string $dockerfile Path within the build context to the Dockerfile. This is ignored if remote is specified and points to an individual filename.
     *     @var string $t A repository name (and optionally a tag) to apply to the resulting image in case of success.
     *     @var string $remote A Git repository URI or HTTP/HTTPS URI build source. If the URI specifies a filename, the file’s contents are placed into a file called Dockerfile.
     *     @var bool $q Suppress verbose build output.
     *     @var bool $nocache Do not use the cache when building the image.
     *     @var string $pull Attempt to pull the image even if an older image exists locally
     *     @var bool $rm Remove intermediate containers after a successful build (default behavior).
     *     @var bool $forcerm always remove intermediate containers (includes rm)
     *     @var int $memory Set memory limit for build.
     *     @var int $memswap Total memory (memory + swap), -1 to disable swap.
     *     @var int $cpushares CPU shares (relative weight).
     *     @var string $cpusetcpus CPUs in which to allow execution (e.g., 0-3, 0,1).
     *     @var int $cpuperiod The length of a CPU period in microseconds.
     *     @var int $cpuquota Microseconds of CPU time that the container can get in a CPU period.
     *     @var int $buildargs Total memory (memory + swap), -1 to disable swap.
     *     @var string $Content-type  Set to 'application/tar'.
     *     @var string $X-Registry-Config A base64-url-safe-encoded Registry Auth Config JSON object
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function build($inputStream, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('dockerfile', null);
        $queryParam->setDefault('t', null);
        $queryParam->setDefault('remote', null);
        $queryParam->setDefault('q', false);
        $queryParam->setDefault('nocache', false);
        $queryParam->setDefault('pull', null);
        $queryParam->setDefault('rm', true);
        $queryParam->setDefault('forcerm', false);
        $queryParam->setDefault('memory', null);
        $queryParam->setDefault('memswap', null);
        $queryParam->setDefault('cpushares', null);
        $queryParam->setDefault('cpusetcpus', null);
        $queryParam->setDefault('cpuperiod', null);
        $queryParam->setDefault('cpuquota', null);
        $queryParam->setDefault('buildargs', null);
        $queryParam->setDefault('Content-type', 'application/tar');
        $queryParam->setHeaderParameters(['Content-type']);
        $queryParam->setDefault('X-Registry-Config', null);
        $queryParam->setHeaderParameters(['X-Registry-Config']);
        $url      = '/build';
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $inputStream;
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Create an image either by pulling it from the registry or by importing it.
     *
     * @param string $inputImage Image content if the value - has been specified in fromSrc query parameter
     * @param array  $parameters {
     *
     *     @var string $fromImage Name of the image to pull. The name may include a tag or digest. This parameter may only be used when pulling an image.
     *     @var string $fromSrc Source to import. The value may be a URL from which the image can be retrieved or - to read the image from the request body. This parameter may only be used when importing an image.
     *     @var string $repo Repository name given to an image when it is imported. The repo may include a tag. This parameter may only be used when importing an image.
     *     @var string $tag Tag or digest.
     *     @var string $X-Registry-Auth A base64-encoded AuthConfig object
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create($inputImage, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('fromImage', null);
        $queryParam->setDefault('fromSrc', null);
        $queryParam->setDefault('repo', null);
        $queryParam->setDefault('tag', null);
        $queryParam->setDefault('X-Registry-Auth', null);
        $queryParam->setHeaderParameters(['X-Registry-Auth']);
        $url      = '/images/create';
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $inputImage;
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Return low-level information on the image name.
     *
     * @param string $name       Image name or id
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\Image
     */
    public function find($name, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/images/{name}/json';
        $url        = str_replace('{name}', urlencode($name), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\Image', 'json');
            }
        }

        return $response;
    }

    /**
     * Return the history of the image name.
     *
     * @param string $name       Image name or id
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\ImageHistoryItem[]
     */
    public function history($name, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/images/{name}/history';
        $url        = str_replace('{name}', urlencode($name), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\ImageHistoryItem[]', 'json');
            }
        }

        return $response;
    }

    /**
     * Push the image name on the registry.
     *
     * @param string $name       Image name or id
     * @param array  $parameters {
     *
     *     @var string $tag The tag to associate with the image on the registry.
     *     @var string $X-Registry-Auth A base64-encoded AuthConfig object
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function push($name, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('tag', null);
        $queryParam->setRequired('X-Registry-Auth');
        $queryParam->setHeaderParameters(['X-Registry-Auth']);
        $url      = '/images/{name}/push';
        $url      = str_replace('{name}', urlencode($name), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Tag the image name into a repository.
     *
     * @param string $name       Image name or id
     * @param array  $parameters {
     *
     *     @var string $repo The repository to tag in.
     *     @var string $tag The new tag name.
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function tag($name, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('repo', null);
        $queryParam->setDefault('tag', null);
        $url      = '/images/{name}/tag';
        $url      = str_replace('{name}', urlencode($name), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Remove the image name from the filesystem.
     *
     * @param string $name       Image name or id
     * @param array  $parameters {
     *
     *     @var string $force 1/True/true or 0/False/false, default false
     *     @var string $noprune 1/True/true or 0/False/false, default false.
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function remove($name, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('force', null);
        $queryParam->setDefault('noprune', null);
        $url      = '/images/{name}';
        $url      = str_replace('{name}', urlencode($name), $url);
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('DELETE', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Search for an image on Docker Hub.
     *
     * @param array $parameters {
     *
     *     @var string $term Term to search
     *     @var int $limit Maximum returned search results
     *     @var string $filters A JSON encoded value of the filters (a map[string][]string) to process on the images list.
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\ImageSearchResult[]
     */
    public function search($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('term', null);
        $queryParam->setDefault('limit', null);
        $queryParam->setDefault('filters', null);
        $url      = '/images/search';
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('200' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\ImageSearchResult[]', 'json');
            }
        }

        return $response;
    }

    /**
     * Create a new image from a container’s changes.
     *
     * @param \Docker\API\Model\ContainerConfig $containerConfig The container configuration
     * @param array                             $parameters      {
     *
     *     @var string $container Container id or name to commit
     *     @var string $repo Repository name for the created image
     *     @var string $tag Tag name for the create image
     *     @var string $comment Commit message
     *     @var string $author author (e.g., “John Hannibal Smith <hannibal@a-team.com>“)
     *     @var string $pause 1/True/true or 0/False/false, whether to pause the container before committing
     *     @var string $changes Dockerfile instructions to apply while committing
     *     @var string $Content-Type Content Type of input
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface|\Docker\API\Model\CommitResult
     */
    public function commit(\Docker\API\Model\ContainerConfig $containerConfig, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('container', null);
        $queryParam->setDefault('repo', null);
        $queryParam->setDefault('tag', null);
        $queryParam->setDefault('comment', null);
        $queryParam->setDefault('author', null);
        $queryParam->setDefault('pause', null);
        $queryParam->setDefault('changes', null);
        $queryParam->setDefault('Content-Type', 'application/json');
        $queryParam->setHeaderParameters(['Content-Type']);
        $url      = '/commit';
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $this->serializer->serialize($containerConfig, 'json');
        $request  = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);
        if (self::FETCH_OBJECT == $fetch) {
            if ('201' == $response->getStatusCode()) {
                return $this->serializer->deserialize((string) $response->getBody(), 'Docker\\API\\Model\\CommitResult', 'json');
            }
        }

        return $response;
    }

    /**
     * Get a tarball containing all images and metadata for the repository specified by name.
     *
     * @param string $name       Image name or id
     * @param array  $parameters List of parameters
     * @param string $fetch      Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function save($name, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/images/{name}/get';
        $url        = str_replace('{name}', urlencode($name), $url);
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $queryParam->buildFormDataString($parameters);
        $request    = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Get a tarball containing all images and metadata for one or more repositories.
     *
     * @param array $parameters {
     *
     *     @var array $names Image names to filter
     * }
     *
     * @param string $fetch Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function saveAll($parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $queryParam->setDefault('names', null);
        $url      = '/images/get';
        $url      = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers  = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body     = $queryParam->buildFormDataString($parameters);
        $request  = $this->messageFactory->createRequest('GET', $url, $headers, $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Load a set of images and tags into a Docker repository. See the image tarball format for more details.
     *
     * @param string $imagesTarball Tar archive containing images
     * @param array  $parameters    List of parameters
     * @param string $fetch         Fetch mode (object or response)
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function load($imagesTarball, $parameters = [], $fetch = self::FETCH_OBJECT)
    {
        $queryParam = new QueryParam();
        $url        = '/images/load';
        $url        = $url . ('?' . $queryParam->buildQueryString($parameters));
        $headers    = array_merge(['Host' => 'localhost'], $queryParam->buildHeaders($parameters));
        $body       = $imagesTarball;
        $request    = $this->messageFactory->createRequest('POST', $url, $headers, $body);
        $response   = $this->httpClient->sendRequest($request);

        return $response;
    }
}

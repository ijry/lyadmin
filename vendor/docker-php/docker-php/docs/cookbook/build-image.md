# Building an Image

This library provides the endpoint for `/build` url in the docker remote API in the `ImageManager`:

```php
/**
 * Build an image from Dockerfile via stdin.
 *
 * @param string $inputStream The input stream must be a tar archive compressed with one of the following algorithms: 
 *                            identity (no compression), gzip, bzip2, xz.
 * @param array  $parameters  {
 *
 *     @var string $dockerfile Path within the build context to the Dockerfile. This is ignored if remote is specified 
 *                             and points to an individual filename.
 *     @var string $t A repository name (and optionally a tag) to apply to the resulting image in case of success.
 *     @var string $remote A Git repository URI or HTTP/HTTPS URI build source. If the URI specifies a filename, 
 *                         the file’s contents are placed into a file called Dockerfile.
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
public function build($inputStream, $parameters = [], $fetch = self::FETCH_OBJECT);
```

In order to build an image you need to provide the `$inputStream` variable which correspond to the tar binary of a 
folder containing a `Dockerfile` (or another name by using the `dockerfile` parameters) and other files used during the
build.

Since `Docker` build directory can be heavy, Docker PHP override this call and allows passing a `resource` or a 
`Psr\Http\Message\StreamInterface` instance. This avoid using too much memory in PHP.

## Build return

This function can return 3 different objects depending on the value of the `$fetch` parameter:
 
### ImageManager::FETCH_OBJECT

This is default mode, where this function will block until the build is finished and return an array of `BuildInfo` 
object.

This object contains the log of the build:

```php
$docker = new Docker();

$imageManager = $docker->getImageManager();
$buildInfos = $imageManager->build($inputStream);

foreach ($buildInfos as $buildInfo) {
    echo $buildInfo->getStream();
}
```

### ImageManager::FETCH_STREAM

Use this mode if you want to stream, in real time, the log, of your build. It returns a `BuildStream` which accept to 
add callback with the `onFrame` method. Once all callback have been set you need to call the `wait` to really read the
stream in real time.

The callback will receive a `BuildInfo` object once a line is available:

```php
$buildStream = $imageManager->build($inputStream, [], ContainerManager::FETCH_STREAM);
$buildStream->onFrame(function (BuildInfo $buildInfo) {
    echo $buildInfo->getStream();
});

$buildStream->wait();
```

Since the build stream does not wait by default the end of the response, you can use this mode to do others things 
while your image is being built. However if you never call the wait method, and build is not finish before the 
execution of your PHP script, it will be canceled as the connection will be terminated.


### ImageManager::FETCH_RESPONSE

The build function will return the raw [PSR7](http://www.php-fig.org/psr/psr-7/) Response. It's up too you handle 
decoding and receiving correct output in this case.

## Context

Docker PHP provides a `ContextInterface` and a default `Context` object for creating the `$inputStream` of the build
method.

```php
$context = new Context('/path/to/my/docker/build');
$inputStream = $context->toStream();

$imageManager->build($inputStream);
```

You can safely use this context object to build image with a huge directory to size without consuming any memory or disk
on the PHP side as it will directly pipe the output of a `tar` process into the Docker Remote API.

### Context Builder

Additionally you can use the `ContextBuilder` to have a dynamic generation of your `Dockerfile`:

```php
$contextBuilder = new ContextBuilder();
$contextBuilder->from('ubuntu:latest');
$contextBuilder->run('apt-get update && apt-get install -y php5');

$imageManager->build($contextBuilder->getContext()->toStream());
```


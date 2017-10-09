# Running a container

Running a container in Docker PHP like it would be done with the docker client `docker run image command` is not a
single call to api, even with the docker run command, it involves multiple calls to the API.

## Creating the container

First step is to create a container and its associated configuration, by creating a `ContainerConfig` instance and 
passing to the `create` api endpoint.

```php
use Docker\Docker;                   
use Docker\API\Model\ContainerConfig;

$docker = new Docker();
$containerManager = $docker->getContainerManager();

$containerConfig = new ContainerConfig();
$containerConfig->setImage('busybox:latest');
$containerConfig->setCmd(['echo', 'I am running a command']);

$containerCreateResult = $containerManager->create($containerConfig);
```

This will return a `ContainerCreateResult` object with the id of the created container. If you don't want to use 
container id you can also specify a unique name for this container:

```php
$containerCreateResult = $containerManager->create($containerConfig, ['name' => 'my-container-unique-name']);
```

Be aware that the container is immutable if you need to change a configuration for a container, you will need to remove
the existing one and create it again with the new configuration.

## Starting the container

Once a container has been created, you can start it, this will in fact, only launch the process inside the isolated 
container. This is done with the `start` method of the `ContainerManager`, you can use the id of the container or the
name:

```php
$containerManager->start($containerCreateResult->getId());
// Or
$containerManager->start('my-container-unique-name');
```

The start method will always return the raw [PSR7](http://www.php-fig.org/psr/psr-7/) Response, but you don't need 
to check it as on failure it will throw an exception.

## Waiting for the container to end

Once your container is started you can wait for it end by using the `wait` method, be aware that your PHP script will
block until the container has stopped or that the default timeout set on the client has been reached (default to 60 
seconds)

```php
$containerManager->wait('my-container-unique-name');
```

## Reading logs in real time

Sometimes you will need to read logs in real time for a container. You can use the `attach` method for that. 
Be aware that you will only receive them if you configure the container with 
[son log driver](https://docs.docker.com/engine/reference/logging/overview/), which is the default configuration.

```php
$attachStream = $containerManager->attach('my-container-unique-name');
```

The `$attachStream` returned will be an instance of a `DockerRawStream`. You can use this object afterwards to add 
callbacks on the different streams:

 * `addStdin` to add a callback on the stdin stream
 * `addStdout` for the stdout stream
 * `addStderr` for the stderr stream

The callback for each of this method takes a string for the first argument which correspond to the log line. 

Use then the wait method to activate real time logging, this method will only stop when then stream is closed which
correspond to when the container has stopped.

```php
$attachStream->onStdout(function ($stdout) {
    echo $stdout;
});
$attachStream->onStderr(function ($stderr) {
    echo $stderr;
});

$attachStream->wait();
```

If you follow all the example, you will not see the log and this normal. In fact the container and the call to the attach
method need extra configuration:

```php
$containerConfig = new ContainerConfig();
$containerConfig->setImage('busybox:latest');
$containerConfig->setCmd(['echo', 'I am running a command']);
$containerConfig->setNames(['my-container-unique-name']]);
// You need to attach stream of the container to docker
$containerConfig->setAttachStdin(true);
$containerConfig->setAttachStdout(true);
$containerConfig->setAttachStderr(true);

$containerManager->create($containerConfig);

// You also need to set stream to true to get the logs, and tell which stream you want to attach
$attachStream = $containerManager->attach('my-container-unique-name', [
    'stream' => true,
    'stdin' => true,
    'stdout' => true,
    'stderr' => true
]);
$containerManager->start('my-container-unique-name');

$attachStream->onStdout(function ($stdout) {
    echo $stdout;
});
$attachStream->onStderr(function ($stderr) {
    echo $stderr;
});

$attachStream->wait();
```

If you read the following example, you will notice that we call the `attach` method before starting the container with
`start`. This is normal, as otherwise during the time the container is started and the call to the `attach` endpoint
some logs may have been processed and you will loose this information. That's why it is strongly recommended to attach 
the container before starting it.

## Interacting with a container

WIth the last example we can now read the log a container in real time. However you may need to send input to this 
container. This can be done by attaching a websocket with the `attachWebsocket` method of the `ContainerManager`

```php
$webSocketStream = $containerManager->attachWebsocket('my-container-unique-name', [
    'stream' => true,
    'stdout' => true,
    'stderr' => true,
    'stdin'  => true,
]);
```

The returned stream will be an instance of `AttachWebsocketStream` and it can be used to both reading and writing to
the container:

```php
$line = $webSocketStream->read();
$websocketStream->write('i send input to the container');
```

If the `$line` is `null` then the stream is no longer available (container is certainly stopped), if it's equal to 
`false` then the stream is still available but no output was received, otherwise it will return output from the container.

To actually write on the stream (having the stdin stream open) you will, again, need extra configuration when creating
the container:

```php
// Open the stdin stream from docker engine to the container
$containerConfig->setOpenStdin(true);
// Needed if you want to use process that rely on a tty, be aware as there is, in fact, no tty this may cause bug to
// the underlying process in your container
$containerConfig->setTty(true);
```

Be aware that there is no distinction between stdout and stderr in this mode.

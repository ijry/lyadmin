# Connecting to Docker

## Default, with environment variables

By default, Docker-PHP uses the the same environment variables as the Docker command line to connect to a running `docker daemon`:
 
 * `DOCKER_HOST`: tcp address for the docker daemon (i.e. tcp://127.0.0.1:2376)
 * `DOCKER_TLS_VERIFY`: if set to true use tls for authentication of the client
 * `DOCKER_CERT_PATH`: path for the client certificates to use for authentication
 * `DOCKER_PEER_NAME`: peer name of the docker daemon (as set in the certificate)
 
If you use `docker-machine` you can set this environment variables with the `env` command.
 
If the `DOCKER_HOST` environment variable is not set, it will use `unix:///var/run/docker.sock` as the default tcp address.

```php
<?php

use Docker\Docker;

$docker = new Docker();
```

## Custom connection

You can connect to an arbitrary server by passing an instance of `Docker\DockerClient` to `Docker\Docker` :

```php
<?php

$client = new Docker\DockerClient([
    'remote_socket' => 'tcp://127.0.0.1:2375',
    'ssl' => false,
]);
$docker = new Docker\Docker($client);
```

Since `DockerClient` is a decorator around a `Http\Client\Socket\Client`, you can go on the 
[official documentation of the socket client](http://docs.php-http.org/en/latest/clients/socket-client.html)
to learn about possible options.

## Custom client

In fact `Docker\Docker` accept any client from [Httplug](http://httplug.io/) (respecting the `Http\Client\HttpClient` interface).

So you can use [React](https://github.com/reactphp/http-client), [Guzzle](http://docs.guzzlephp.org/en/latest/) 
or any [other adapters / clients](http://docs.php-http.org/en/latest/clients.html).


```php
<?php

use Docker\Docker;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

$config = [
    // Config params
];
$guzzle = new GuzzleClient($config);
$adapter = new GuzzleAdapter($guzzle);
$docker = new Docker\Docker($adapter);
```

However not all clients fully support Docker daemon features, such as unix socket domain connection, real time streaming, ...
That's why it's strongly encouraged to use the [Socket Http Client](http://docs.php-http.org/en/latest/clients/socket-client.html)
which support all the docker daemon features.

Also this client needs to be decorated by [a plugin system](http://docs.php-http.org/en/latest/plugins/index.html). 
At least 2 plugins are required:

 * [Content-Length Plugin](http://docs.php-http.org/en/latest/plugins/content-length.html): Which will set correct header `Content-Length` header for the request;
 * [Decoder Plugin](http://docs.php-http.org/en/latest/plugins/decoder.html): Which allow to manipulate chunked and/or encoded response
 
We also use, by default, the [Error Plugin](http://docs.php-http.org/en/latest/plugins/error.html) to transform bad responses (400 - 599) into exceptions.

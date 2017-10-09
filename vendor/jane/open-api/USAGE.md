# Usage

This documentation provide a full example to create and use a API Client generated with this library.

Be aware that this documentation reflect features that are only available from 1.3.0, please upgrade or
use this version if you want to follow the following example.

## Installation

First step is to install this package:

```
composer require --dev jane/open-api
```

We install this library as a dev one, as there is no need for your user or the runtime environment to
be aware of the generation stuff.

However the generated library will have some dependencies on other package and class on jane/open-api.
For that Jane OpenAPI offer a runtime library that you MUST depend on by requiring into your non-dev
dependencies:

```
composer require jane/openapi-runtime
```

## Creating your schema

You may need to create an OpenAPI Specification if the API doesn't give you one, for that
please refer to this documentation on how to create it: https://github.com/OAI/OpenAPI-Specification/blob/master/versions/2.0.md

For this example we will assume that you have a `swagger.json` file at the root of your
repository with the following content:

```json
{
    "swagger": "2.0",
    "paths": {
        "/foo": {
            "get": {
                "operationId": "all",
                "responses": {
                    "200": {
                        "description": "no error",
                        "schema": {
                            "type": "array",
                            "items": {
                                "$ref": "#/definitions/Foo"
                            }
                        }
                    }
                },
                "tags": ["Foo"]
            },
            "post": {
                "operationId": "create",
                "responses": {
                    "201": {
                        "description": "no error"
                    }
                },
                "parameters": [{
                    "name": "foo",
                    "in": "body",
                    "description": "Foo object to create",
                    "schema": {
                        "$ref": "#/definitions/Foo"
                    }
                }],
                "tags": ["Foo"]
            }
        },
        "/foo/{id}": {
            "get": {
                "operationId": "get",
                "responses": {
                    "200": {
                        "description": "no error",
                        "schema": {
                            "$ref": "#/definitions/Foo"
                        }
                    }
                },
                "parameters": [{
                    "name": "id",
                    "in": "path",
                    "required": true,
                    "description": "The foo identifier",
                    "type": "string"
                }],
                "tags": ["Foo"]
            }
        }
    },
    "definitions": {
        "Foo": {
            "type": "object",
            "properties": {
                "bar": {
                    "type": "string"
                }
            }
        }
    }
}
```

This API define 3 endpoints:

 * GET `/foo` which returns a collection of `Foo`
 * POST `/foo` which creates a `Foo`
 * GET `/foo/{id}` which returns a `Foo` given an `id`

The `Foo` consist of an object having a property `bar` (typehint as a string).

## Generating the code

Once you have your swagger schema you can generate the API Client.

### Configuration

First you will need to specify some configurations options for this library by
creating a `.jane-openapi` file at the root of your repository which returns
a configuration array for Jane OpenAPI:

```php
<?php

return [
    'openapi-file' => __DIR__ . '/swagger.json',
    'namespace' => 'My\API\Client',
    'directory' => __DIR__ . '/generated',
];
```

Available parameters:

 * `openapi-file`: Path of your OpenAPI Specification
 * `namespace`: Namespace prefix of the generated files
 * `directory`: Location of the generated files

It is highly recommanded to generate the file in a other directory than
your sources to not tempt user to modify these files.

### Executing the command

Then you can generate the library by using the following command:

```
./vendor/bin/jane-openapi generate
```

This will create all the needed files in the `generated` directory of your
project:

 * The `Model` directory will contain all the value objects used by your API (`Foo` class in this example)
 * The `Normalizer` directory will contain normalizers that are able to normalize and denormalize all value
 objects created (`FooNormalizer` here) and also a `NormalizerFactory` which allow to get all the created
 normalizers.
 * The `Resource` directory will contain class that are able to call all endpoints
 specified in the API.

### Resource Naming

Naming of the resource depends directly of the name of the tag in your specification:

```
    ...
    "tags": ["Foo"]
    ...
```

Each different tag will create a different resource. I.e. if you specify multiple
tags then your endpoint will be duplicated in the resource.

If there is no tag for an endpoint, then this library will create a `DefaultResource`
containing this endpoint.

### Autoload

Be aware that, if you use a different directory for the generated files,
you must add this directory to the autoload section of your composer file:

```json
"autoload": {
    "psr-4": {
        ...
        "My\\API\\Client\\": "generated/"
    }
}
```

## Using your API Client

Now that the library is generated, you can use it.

To use this library, you will use the generated `Resource` classes,
in our example we will use the `FooResource`.

```
$resource = new My\API\Client\Resource\FooResource($httpClient, $requestFactory, $serializer);
```

You can notice that your API depends on 3 specifics objects:

### HTTP Client

`$httpClient` is a implementation of the `HttpClient` and/or the `HttpAsyncClient` interface
from [HTTPlug](http://httplug.io/).

This allow use to most of the client implementations availble in PHP (guzzle, react, zend, cakephp, ...).

To read more about this consult the [HTTPlug documentation](http://docs.php-http.org/en/latest/httplug/introduction.html).

This client is used to make the calls to your API. By default there are made in an asynchronous mode, if the implementation
does not support async it will fallback to the sync mode.

For this example we use Guzzle 6:


```
composer require php-http/guzzle6-adapter
```

```php
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use GuzzleHttp\Client as GuzzleClient;

$httpClient = new GuzzleAdapter(new GuzzleClient([]));
```

### Request Factory

The `$requestFactory` allows to create and use a PSR7 Request for the calls.

This library doesn't stick to a particular implementation that's why you must provide a factory
to be able to create a PSR7 Request from [Guzzle](https://github.com/guzzle/psr7),
[Diactoros](https://github.com/zendframework/zend-diactoros) or
[any another PSR7 implementation](https://packagist.org/providers/psr/http-message-implementation).

[You can learn more about this on this documentation](http://docs.php-http.org/en/latest/message/message-factory.html).

For this example we use Guzzle implementation:

```
composer require guzzlehttp/psr7 php-http/message
```

```php
use Http\Message\MessageFactory\GuzzleMessageFactory;

$requestFactory = new GuzzleMessageFactory();
```

### Serializer

The `$serializer` service is used to serialize your value objects into json or
deserialize json into value objects.

The recommended way to create this service is to use the following code:

```php
use Joli\Jane\Runtime\Encoder\RawEncoder;
use My\API\Client\Normalizer\NormalizerFactory;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

$serializer = new Serializer(
    NormalizerFactory::create(),
    [
        new JsonEncoder(
            new JsonEncode(),
            new JsonDecode()
        ),
        new RawEncoder()
    ]
);
```

You may want to tweak this serializer for advanced usage (like using XML, or others
thing no supported by this library).

However there is some things that you need to be aware of:

 * The `NormalizerFactory::create()` is a generated method that allow to pass
 all the created normalizer when creating your API Client
 * The `JsonDecode` decoder must be able to transform JSON objects into `\stdClass`.
 As this is the only way, in PHP, to distinguate between an empty collection `[]` and
 an empty object `{}`
 * The `RawEncoder` is used for deep serialization (like an object having a property on another object).

### Making calls

So your setup will look at something like this:

```php
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Joli\Jane\Runtime\Encoder\RawEncoder;
use My\API\Client\Normalizer\NormalizerFactory;
use My\API\Client\Resource\FooResource;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

$httpClient = new GuzzleAdapter(new GuzzleClient([]));
$requestFactory = new GuzzleMessageFactory();
$serializer = new Serializer(
    NormalizerFactory::create(),
    [
        new JsonEncoder(
            new JsonEncode(),
            new JsonDecode()
        ),
        new RawEncoder()
    ]
);

$fooResource = new FooResource($httpClient, $requestFactory, $serializer);
```

To get all of your `Foo` objects you just need to do the following call:

```php
$foos = $fooResource->all();

foreach ($foos as $foo) {
    echo $foo->getBar();
}
```

You can also create a `Foo`:

```php
$newFoo = new Foo();
$newFoo->setBar('bar_value');

$fooResource->create($newFoo);
```

Or get a specific `Foo`:

```php
echo $fooResource->get(15)->getBar();
```

Generated resources have a very complete PHPDoc for each endpoint, you should
rely on it for more complex cases.

### Advanced usage

By default each endpoint will try to deserialize the `Response` from the API
into value objects.

If there is no possible deserialization it will return a PSR7 ResponseInterface.

However you may want to always fetch a PSR7 ResponseInterface or make the call in an
asynchronous mode. This is possible by setting the last parameter of the endpoint method,
but if you need the value objets you will have to use the serializer yourself:

#### Get a PSR7 ResponseInterface

```php
$response = $fooResource->all([], FooResource::FETCH_RESPONSE);

$foos = $serializer->deserialize($response, Foo::class, 'json');
```

#### Make an async call and get a Promise (as defined by HTTPlug Async interface)

```php
$promise = $fooResource->all([], FooResource::FETCH_PROMISE);

...

$response = $promise->wait();
$foos = $serializer->deserialize($response, Foo::class, 'json');
```

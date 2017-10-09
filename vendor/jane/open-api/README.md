# Jane Open Api

[![Latest Version](https://img.shields.io/github/release/janephp/openapi.svg?style=flat-square)](https://github.com/janephp/openapi/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/janephp/openapi.svg?style=flat-square)](https://travis-ci.org/janephp/openapi)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/janephp/openapi.svg?style=flat-square)](https://scrutinizer-ci.com/g/janephp/openapi)
[![Quality Score](https://img.shields.io/scrutinizer/g/janephp/openapi.svg?style=flat-square)](https://scrutinizer-ci.com/g/janephp/openapi)
[![Total Downloads](https://img.shields.io/packagist/dt/jane/open-api.svg?style=flat-square)](https://packagist.org/packages/jane/open-api)

Generate a PHP Client API (PSR7 compatible) given a [OpenApi (Swagger) specification](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/2.0.md).

## Disclaimer

The generated code may contain bug or incorrect behavior, use it as a base for your application but you should never trust it as is.

## Usage

```
# jane-openapi [schema-path] [namespace] [destination]
php vendor/bin/jane-openapi generate swagger.json Name\\Space src/Name/Space
```

This will generate, in the `src/Name/Space`, a Resource, a Model and a Normalizer directory from the swagger.json file:

 * Resource directory will contain all differents resources of the API with their endpoints;
 * Model directory will contain all Model used in the API;
 * Normalizer directory will contain a normalizer service class for each of the model class generated.

### Using a config file

Since 1.3 you can now use a config file for generating your library, this avoid remember the same options each time and track change over the
generation configuration.

For that you need to create a `.jane-openapi` at the root of your repository (you can also use a different name but you
will need to indicate the location of this file to the generate command):

```php
<?php

return [
    'openapi-file' => __DIR__ . '/swagger.json', // Location of our OpenAPI Specification
    'namespace' => 'Namespace\Prefix', // namespace of the generated code
    'directory' => __DIR__ . '/src/Namespace/Prefix', // directory where the code will be output
    'date-format' => \DateTime::RFC3339, // format of the date that your use (you should not set it unless you have to deal with a non compliant specification)
    'reference' => true, // Add the JSON Reference specification to the generated library (so data on the API can use reference like described in https://tools.ietf.org/html/draft-pbryan-zyp-json-ref-03)
]
```

### Tutorial

See [USAGE documentation](USAGE.md) for a more complete tutorial.

## Example

The [Docker PHP](https://github.com/docker-php/docker-php) library has been built on this, you can see there a complete example of using this library.

## Installation

Use composer for installation

```
composer require jane/open-api
```

## Recommended workflow

Here is a recommended workflow when dealing with the generated code:

 1. Start from a clean revision on your project (no modified files);
 2. Update your OpenApi (Swagger) Schema file (edit or download new version);
 3. Generate the new code;
 4. Check the generated code with a diff tool: `git diff` for example;
 5. If all is well commit modifications.

An optional and recommanded practice is to separate the generated code in a specific directory
like creating a `generated` directory in your project and using jane inside. This allows other developers
to be aware that this part of the project is generated and must not be updated manually.

## Internal

Here is a quick presentation on how this library transforms a Json Schema file into models and normalizers:

 1. First step is to read and parse the OpenApi (Swagger) Schema file;
 2. Second step is to guess api calls, classes and their associated properties and types;
 3. Once all things are guessed, they are transformed into an AST (by using the [PHP-Parser library from nikic](https://github.com/nikic/PHP-Parser));
 4. Then the AST is written into PHP files.
 5. Optionally, if php-cs-fixer is present, it is used to format the generated code

## Credits

* [All contributors](https://github.com/janephp/openapi/graphs/contributors)
* [JoliCode](https://jolicode.com) for giving me time on my work to maintain this library

## License

View the [LICENSE](LICENSE) file attach to this project.

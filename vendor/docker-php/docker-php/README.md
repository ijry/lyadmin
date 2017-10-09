Docker PHP
==========

**Docker PHP** (for lack of a better name) is a [Docker](http://docker.com/) client written in PHP.
This library aim to reach 100% API support of the Docker Engine.

The test suite currently passes against the [Docker Remote API v1.24](http://docs.docker.com/reference/api/docker_remote_api_v1.24/).

[![Documentation Status](https://readthedocs.org/projects/docker-php/badge/?version=latest)](http://docker-php.readthedocs.org/en/latest/)
[![Latest Version](https://img.shields.io/github/release/docker-php/docker-php.svg?style=flat-square)](https://github.com/docker-php/docker-php/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/docker-php/docker-php.svg?branch=master&style=flat-square)](https://travis-ci.org/docker-php/docker-php)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/docker-php/docker-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/docker-php/docker-php)
[![Quality Score](https://img.shields.io/scrutinizer/g/docker-php/docker-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/docker-php/docker-php)
[![Total Downloads](https://img.shields.io/packagist/dt/docker-php/docker-php.svg?style=flat-square)](https://packagist.org/packages/docker-php/docker-php)



Installation
------------

The recommended way to install Docker PHP is of course to use [Composer](http://getcomposer.org/):

```bash
composer require docker-php/docker-php
```

Usage
-----

See [the documentation](http://docker-php.readthedocs.org/en/latest/).

Unit Tests
----------

Setup the test suite using [Composer](http://getcomposer.org/) if not already done:

```
$ composer install --dev
```

Run it using [PHPUnit](http://phpunit.de/):

```
$ composer test
```

### Running Tests with `docker-compose`

```
$ docker-compose run php54
$ docker-compose run php55
$ docker-compose run php56
$ docker-compose run php70
```

It is recommended to run only one service during testing, since `composer` requirements may depend on the PHP version and
we are using a host-volume during local testing.

Contributing
------------

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


Versioning
----------

Docker PHP does not follow the classic [semver versioning](http://semver.org/), but follow the Docker Remote API
versioning starting at the v1.21, the master branch will follow the next major release on the Docker Remote API.

Changes on the core of this library not related to the API, will be merged back to previous versions
on a best effort basis.

Credits
-------

This README heavily inspired by [willdurand/Negotiation](https://github.com/willdurand/Negotiation) by @willdurand. This guy is pretty awesome.


License
-------

The MIT License (MIT). Please see [License File](LICENSE) for more information.

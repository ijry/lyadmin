# Symfony Yaml Serializer Encoder/Decoder

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fitbug/symfony-yaml-serializer-encoder-decoder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fitbug/symfony-yaml-serializer-encoder-decoder/?branch=master)
[![Build Status](https://travis-ci.org/fitbug/symfony-yaml-serializer-encoder-decoder.svg?branch=master)](https://travis-ci.org/fitbug/symfony-yaml-serializer-encoder-decoder)
[![Dependency Status](https://www.versioneye.com/user/projects/57adb171bd0cfa002e137ac4/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/57adb171bd0cfa002e137ac4)
[![Latest Stable Version](https://poser.pugx.org/fitbug/symfony-yaml-serializer-encoder-decoder/v/stable)](https://packagist.org/packages/fitbug/symfony-yaml-serializer-encoder-decoder)
[![License](https://poser.pugx.org/fitbug/symfony-yaml-serializer-encoder-decoder/license)](https://packagist.org/packages/fitbug/symfony-yaml-serializer-encoder-decoder)

This package is a Yaml Encoder and Decoder for the [Symfony Serializer
component].

## Getting Started

### Prerequisities

You'll need to install:

 * PHP (Minimum 5.6)

### Installing

```bash
composer require fitbug/symfony-yaml-serializer-encoder-decoder
```

## Usage

```
$encoders        = [new YamlEncoder(new YamlEncode(), new YamlDecode())];
$normalizers     = NormalizerFactory::create();
$serializer      = new Serializer($normalizers, $encoders);
```

See the [Symfony Serializer component].


## Running the tests

First checkout the library, then run

```bash
composer install
```

### Coding Style

We follow PSR2, and also enforce PHPDocs on all functions. To run the tests for coding style violations

```bash
vendor/bin/phpcs -p --standard=psr2 src/
```

### Unit tests

We use PHPSpec for unit tests. To run the unit tests

```bash
vendor/bin/phpspec run
```

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code
of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions
available, see the [tags on this repository](https://github.com/fitbug/symfony-yaml-serializer-encoder-decoder/tags).

## Authors

See the list of [contributors](https://github.com/fitbug/symfony-yaml-serializer-encoder-decoder/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.


[Symfony Serializer component]: https://symfony.com/doc/current/components/serializer.html

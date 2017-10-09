# Socket Client for PHP HTTP

[![Latest Version](https://img.shields.io/github/release/php-http/socket-client.svg?style=flat-square)](https://github.com/php-http/socket-client/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/php-http/socket-client.svg?branch=master&style=flat-square)](https://travis-ci.org/php-http/socket-client)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/php-http/socket-client.svg?style=flat-square)](https://scrutinizer-ci.com/g/php-http/socket-client)
[![Quality Score](https://img.shields.io/scrutinizer/g/php-http/socket-client.svg?style=flat-square)](https://scrutinizer-ci.com/g/php-http/socket-client)
[![Total Downloads](https://img.shields.io/packagist/dt/php-http/socket-client.svg?style=flat-square)](https://packagist.org/packages/php-http/socket-client)

The socket client use the stream extension from PHP, which is integrated into the core.

## Features

 * TCP Socket Domain (tcp://hostname:port)
 * UNIX Socket Domain (unix:///path/to/socket.sock)
 * TLS / SSL Encyrption
 * Client Certificate (only for php > 5.6)

## Installation and Usage

[Read the documentation at http://docs.php-http.org/en/latest/clients/socket-client.html](http://docs.php-http.org/en/latest/clients/socket-client.html)

## Testing

First launch the http server:

```bash
$ ./vendor/bin/http_test_server > /dev/null 2>&1 &
```

Then generate ssh certificates:

```bash
$ cd ./tests/server/ssl 
$ ./generate.sh
$ cd ../../../ 
```

Now run the test suite:

``` bash
$ composer test
```


## Contributing

Please see our [contributing guide](http://docs.php-http.org/en/latest/development/contributing.html).


## Security

If you discover any security related issues, please contact us at [security@php-http.org](mailto:security@php-http.org).


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

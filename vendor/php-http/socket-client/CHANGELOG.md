# Change Log

## 1.3.0

 * Make sure `Stream::__toString` never throws exception
 * Added more exception
   * `BrokenPipeException`
   * `ConnectionException`
   * `InvalidRequestException`
   * `SSLConnectionException`
 
## 1.2.0

 * Dropped PHP 5.4 support
 * Using stable version of `php-http/discovery`

## 1.1.0

 * Added discovery as hard dependency
 * Reading more bytes than expected in a stream now returns the remaining content instead of throwing an Error

## 1.0.0

Initial release

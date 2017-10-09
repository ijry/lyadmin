Phpuri
=========

A php library for converting relative urls to absolute.

```php
require 'phpuri.php';
echo phpUri::parse('https://www.google.com/')->join('foo');
//==> https://www.google.com/foo
```

### Benchmark
<pre>
php test.php
rel2abs:         successes -> 26, failures => 9, elapsed time: 0.001301
url_to_absolute: successes -> 32, failures => 3, elapsed time: 0.0029089999999999
phpuri:          successes -> 35, failures => 0, elapsed time: 0.002402
</pre>
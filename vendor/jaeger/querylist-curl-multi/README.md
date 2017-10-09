# QueryList-CurlMulti
QueryList Plugin: Curl multi threading. 

QueryList插件: Curl多线程.

php-curlmulti:[https://github.com/ares333/php-curlmulti](https://github.com/ares333/php-curlmulti)

> QueryList:[https://github.com/jae-jae/QueryList](https://github.com/jae-jae/QueryList)

## Installation for QueryList4
```
composer require jaeger/querylist-curl-multi
```

## API
-  CurlMulti **curlMulti($urls = [])**: Set the list of URLs to be collected.

-  class **CurlMulti** 
	- CurlMulti **add($urls)**:Add url task.
	- array **getUrls()**:Get all url.
	- CurlMulti **success(Closure $callback)**:Called if task is success.
	-  CurlMulti **error(Closure $callback)**:Callback for failed tasks.
	-  CurlMulti **start(array $opt = [])**:Start all tasks.This is a blocked method.

## Installation options

 **QueryList::use(CurlMulti::class,$opt1)**
- **$opt1**:`curlMulti` function alias.

## Usage

- Installation Plugin

```php
use QL\QueryList;
use QL\Ext\CurlMulti;

$ql = QueryList::getInstance();
$ql->use(CurlMulti::class);
//or Custom function name
$ql->use(CurlMulti::class,'curlMulti');
```
- Example-1

Collecting GitHub Trending:
```php
$ql->rules([
    'title' => ['h3 a','text'],
    'link' => ['h3 a','href']
])->curlMulti([
    'https://github.com/trending/php',
    'https://github.com/trending/go'
])->success(function (QueryList $ql,CurlMulti $curl,$r){
    echo "Current url:{$r['info']['url']} \r\n";
    $data = $ql->query()->getData();
    print_r($data->all());
})->start();
```
Out:
```
Current url:https://github.com/trending/php
Array
(
    [0] => Array
        (
            [title] => jupeter / clean-code-php
            [link] => /jupeter/clean-code-php
        )
    [1] => Array
        (
            [title] => laravel / laravel
            [link] => /laravel/laravel
        )
    [2] => Array
        (
            [title] => spatie / browsershot
            [link] => /spatie/browsershot
        )
   //....
)

Current url:https://github.com/trending/go
Array
(
    [0] => Array
        (
            [title] => DarthSim / imgproxy
            [link] => /DarthSim/imgproxy
        )
    [1] => Array
        (
            [title] => jaegertracing / jaeger
            [link] => /jaegertracing/jaeger
        )
    [2] => Array
        (
            [title] => jdkato / prose
            [link] => /jdkato/prose
        )
  //...
)

```

- Example-2

```php
$ql->curlMulti('https://github.com/trending/php')
    ->success(function (QueryList $ql,CurlMulti $curl,$r){
        echo "Current url:{$r['info']['url']} \r\n";
        if($r['info']['url'] == 'https://github.com/trending/php'){
            // append a task
            $curl->add('https://github.com/trending/go');
        }
        $data = $ql->find('h3 a')->texts();
        print_r($data->all());
    })
    ->start();
```
Out:
```
Current url:https://github.com/trending/php
Array
(
    [0] => jupeter / clean-code-php
    [1] => laravel / laravel
    [2] => spatie / browsershot
   //...
)

Current url:https://github.com/trending/go
Array
(
    [0] => DarthSim / imgproxy
    [1] => jaegertracing / jaeger
    [2] => jdkato / prose
    //...
)
```

- Example-3

```
$ql->curlMulti([
    'https://github-error-host.com/trending/php',
    'https://github.com/trending/go'
])->success(function (QueryList $ql,CurlMulti $curl,$r){
    echo "Current url:{$r['info']['url']} \r\n";
    $data = $ql->rules([
        'title' => ['h3 a','text'],
        'link' => ['h3 a','href']
    ])->query()->getData();
    print_r($data->all());
})->error(function ($errorInfo,CurlMulti $curl){
    echo "Current url:{$errorInfo['info']['url']} \r\n";
    print_r($errorInfo['error']);
})->start([
    // Max concurrence num, can be changed in the fly.
    'maxThread' => 10,
    // Trigger curl error or user error before max try times reached.If reached $error will be called.
    'maxTry' => 3,
    // Global CURLOPT_* for all tasks.
    'opt' => [
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 1,
        CURLOPT_RETURNTRANSFER => true
    ],
    // Cache is identified by url.If cache finded,the class will not access the network,but return the cache directly.
    'cache' => ['enable' => false, 'compress' => false, 'dir' => null, 'expire' =>86400, 'verifyPost' => false]
]);

```
Out:
```
Current url:https://github.com/trending/go
Array
(
    [0] => Array
        (
            [title] => DarthSim / imgproxy
            [link] => /DarthSim/imgproxy
        )
    [1] => Array
        (
            [title] => jaegertracing / jaeger
            [link] => /jaegertracing/jaeger
        )
    [2] => Array
        (
            [title] => getlantern / lantern
            [link] => /getlantern/lantern
        )
   //...
)

Current url:https://github-error-host.com/trending/php
Array
(
    [0] => 28
    [1] => Resolving timed out after 1000 milliseconds
)
```

- Example-3
```
$ql->rules([
    'title' => ['h3 a','text'],
    'link' => ['h3 a','href']
])->curlMulti()->add('https://github.com/trending/go')
    ->success(function (QueryList $ql,CurlMulti $curl,$r){
        echo "Current url:{$r['info']['url']} \r\n";
        $data = $ql->query()->getData();
        print_r($data->all());
})->start()
    ->add('https://github.com/trending/php')
    ->start();
```
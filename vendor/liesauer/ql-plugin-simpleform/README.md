# QueryList V4 Plugin - SimpleForm
make form submission easier
# Installation
```
composer require liesauer/ql-plugin-simpleform
```
# Bind
* QueryList `simpleForm` ($formUrl, $formSelector = '', $formParams = [], $postParams = [], ...$args)
    * `formUrl` where to get the form
    * `formSelector` only required if there are two or more form elements
    * `formParams` use for get the form, read `QueryList::get` or `QueryList::post`, default method is get
    * `postParams` same as `formParams` but use for form submission, default method is post
    * `args` no used
# Usage
```php
use liesauer\QLPlugin\SimpleForm;
use QL\QueryList;

require_once __DIR__ . '/vendor/autoload.php';

// cookie needed for this example
$cookie = new \GuzzleHttp\Cookie\CookieJar();

$ql = QueryList::getInstance();

// use this plugin
$ql->use(SimpleForm::class);

$username = $ql->simpleForm('https://github.com/login', '', [
    'options' => [
        'verify'  => false,
        'cookies' => $cookie,
    ],
], [
    'params'  => [
        'login'    => 'username',
        'password' => 'password',
    ],
    'options' => [
        'verify'  => false,
        'cookies' => $cookie,
    ],
])->find('.header-nav-current-user>.css-truncate-target')->text();

if (!empty($username)) {
    echo "welcome back, {$username}!\n";
} else {
    $error = $ql->find('.flash-error>.container')->text();
    echo "{$error}\n";
}
```
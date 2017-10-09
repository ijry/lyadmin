[中文文档](README_CN.md "中文文档")

About
-----

This is undoubtedly the best php curl library.It is widely used by many developers.The library is a wrapper of curl_multi_* functions with best performance,maximum flexibility,maximum ease of use and negligible performance consumption.All in all it's a very very powerful library.

Requirement
-----------
PHP: >=5.4

Install
-------
composer require phpdr.net/php-curlmulti

Contact Us
----------
Email: admin@phpdr.com<br>
QQ Group:215348766

Feature
-------
1. Extremely low cpu and memory usage.
1. Best program performance(tested spider 2000+ html pages per second and 1000MBps pic download speed).
1. Support global parallel and seperate parallel for defferent task type.
1. Support running info callback.All info you need is returned, include overall and every task infomation.
1. Support adding task in task callback.
1. Support user callback.You can do anything in that.
1. Support task controll use value returned from process callback .
1. Support global error callback and task error callback.All error info is returned.
1. Support internal max try for tasks.
1. Support user variable flow arbitrarily.
1. Support global CURLOPT_* and task CURLOPT_*.
1. Powerfull cache.Global and task cache config supported.
1. All public property config can be changed on the fly!
1. You can develop amazing curl application based on the library.

Mechanism
---------

Without pthreads php is single-threaded language,so the library widely use callbacks.There are only two common functions CurlMulti_Core::add() and CurlMulti_Core::start().add() just add a task to internal taskpool.start() starts callback cycle with the concurrent number of CurlMulti_Core::$maxThread and is blocked until all added tasks(a typical task is a url) are finished.If you have huge number of tasks you will use CurlMulti_Core::$cbTask to specify a callback function to add() urls,this callback is called when the number of running concurrent is less than CurlMulti_Core::$maxThread and internal taskpool is empty.When a task finished the 'process callback' specified in add() is immediately called,and then fetch a task from internal taskpool,and then add the task to the running concurrent.When all added tasks finished the start() finished.

Files
-----
**src/Core.php**<br>
Kernel class

**src/Base.php**<br>
A wraper of CurlMulti_Core.Very usefull tools and convention is included.It's very easy to use.All spider shoud inherent this class.

**src/AutoClone.php**<br>
A powerfull site clone tool.It's a perfect tool.

<sub>**Feature：**

1. <sub>It's a work of art on software engineer and programming technique.
1. <sub>Easy to use, has only one public method start(void).
1. <sub>Low coupling,easy to extend.Copying a site with CurlMulti is very fast.
1. <sub>All duplicate url in all pages will be processed only once.
1. <sub>All url and uri in pages will be accurately processed automaticly!
1. <sub>@import in css and images in css can be downloaded automaticly,ignore @import depth!
1. <sub>Can process multi url prefix and config the url individually.
1. <sub>Subprefix for url can be specified and config for the subprefix can be specified.
1. <sub>Process 3xx redirect automaticly.
1. <sub>Resources cross site will be shared.For example,site A use js and css of B,when clone B this css and js will not be processed again.
1. <sub>In one dir arbitray number site can be located and no file will conflict.
1. <sub>Download option support multitype control.

<sub>Clone of site: http://manual.phpdr.net/

API(Core)
-------------------
```PHP
public $maxThread = 10
```
Max concurrence num, can be changed in the fly.<br>
The limit may be associated with OS or libcurl,but not the library.

```PHP
public $maxTry = 3
```
Trigger curl error or user error before max try times reached.If reached $cbFail will be called.

```PHP
public $opt = array ()
```
Global CURLOPT_* for all tasks.Overrided by CURLOPT_* in add().

```PHP
public $cache = array ('enable' => false, 'compress' => false, 'dir' => null, 'expire' =>86400, 'verifyPost' => false)
```
The options is very easy to understand.Cache is identified by url.If cache finded,the class will not access the network,but return the cache directly.

```PHP
public $taskPoolType = 'queue'
```
Values are 'stack' or 'queue'.This option decide depth-first or width-first.Default value is 'stack' depth-first.

```PHP
public $cbTask = null
```
When the parallel is less than $maxThread and taskpool is empty the class will try to call callback function specified by $cbTask.

```PHP
public $cbInfo = null
```
Callback for running info.Use print_r() to check the info in callback.The speed is limited once per second.

```PHP
public $cbUser = null
```
Callback for user operations very frequently.You can do anything there.Is called when has network activity.

```PHP
public $cbFail = null
```
Callback for failed tasks.Lower priority than 'fail callback' specified than add().

```PHP
public function add(array $item, $process = null, $fail = null, $ahead = null)
```
Add a task to taskpool.<br>
**$item['opt']=array()** CURLOPT_* for current task.Override the global $this->opt and merged.<br>
**$item['args']** Second parameter for callbacks.Include $this->cbFail and $fail and $process.<br>
**$item['cache']=array()** Task cache.Override $this->cache and merged.<br />
**$process** Called if task is success.The first parameter for the callback is array('info'=>array(),'content'=>'','ext'=>array()) and the second parameter is $item['args'] specified in first parameter of add().First callback parameter's info key is http info,content key is url content,ext key has some extended info.Callback can return array.key:cache(bool,controll caching the current request)<br />
**$fail** Task fail callback.The first parameter has two keys of info and error.Info key is http info.The error key is full error infomation.The second parameter is $item['args'].<br>
**$ahead** bool.Switch for higher priority.

```PHP
public function start()
```
Start the loop.This is a blocked method.

API(Base)
-----------------
```PHP
function hashpath($name)
```
Get hashed path

```PHP
function substr($str, $start, $end = null, $mode = 'g')
```
Get substring between start string and end string.Start and end string are excluded.

```PHP
function cbCurlFail($error, $args)
```
Default fail callback.

```PHP
function cbCurlInfo($info,$isFirst,$isLast)
```
Default CurlMulti_Core::$cbInfo

```PHP
function encoding($html, $in = null, $out = 'UTF-8', $mode = 'auto')
```
Powerfull function to convert html encoding and set \<head\>\</head\> in html.$in can be get from \<head\>\</head\>.

```PHP
function isUrl($str)
```
If is a full url.

```PHP
function uri2url($uri, $urlCurrent)
```
Get full url of $uri used in the $urlCurrent html page.

```PHP
function url2uri($url, $urlCurrent)
```
get relative uri of the current page.

```PHP
function urlDir($url)
```
url should be redirected final url.Final url normally has '/' suffix.

```PHP
function getCurl()
```
Return CurlMulti_Core instance.

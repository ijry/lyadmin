关于
-----

这是目前最好的php curl类库，很多开发者基于此库开发项目。类库是对curl_multi_*系列函数的封装，性能、扩展性、易用性都是最高水平，很强大。

需求
----
PHP: >=5.4

安装
----
composer require phpdr.net/php-curlmulti

联系我们
--------
Email: admin@phpdr.com<br>
QQ群:215348766

特性
----
1. 极低的CPU和内存使用率。
1. 速度在程序层面最高(实测抓取html速度达到2000+页每秒，下载速度1000Mbps)。
1. 支持全局并发设置和根据任务类型单独设置并发。
1. 支持状态回调，运行中的所有信息都被返回，包括单独的每个任务信息。
1. 支持通过回调添加任务。
1. 支持用户自定义回调，可以在回调中做任何事情。
1. 支持成功回调返回值控制任务。
1. 支持全局错误回调和单独任务的错误回调，所有和错误相关的信息都被返回。
1. 支持内部全自动重试。
1. 支持用户参数任意传递。
1. 支持CURLOPT_\*全局设置和单个任务设置。
1. 强大的内置缓存，可以设置全局缓存和单独任务缓存。
1. 所有配置可以在运行中动态改变并生效！
1. 基于此库你可以开发各种强劲的CURL应用。

运行机制
--------

没有pthreads扩展支持，php是单线程顺序执行的，所以本类库大量使用回调函数。类库只有两个常用的方法，add()和start()，add()添加一个任务到内部任务池，start()开始以$maxTrhead设置的并发数进行回调循环，此方法是阻塞的直到所有任务完成。如果有大量的任务需要处理，使用$cbTask指定添加任务的回调函数，当并发不足并且任务池为空时此回调函数被调用。当一个任务完成之后add()中指定的回调立刻被执行，然后curl从任务池取一个任务添加到并发请求中。所有任务完成后start()函数结束。

文件
----
**src/Core.php**<br>
核心库。

**src/Base.php**<br>
核心库的封装，包含非常有用的工具和一些规范。

**src/AutoClone.php**<br>
一个完美的全自动网站克隆工具。

<sub>**特性：**

1. <sub>软件工程，面向对象和编程技巧的完美结晶，和php-curlmulti一样非常具有艺术性。
1. <sub>使用方便，只有一个无参方法start()。
1. <sub>低耦合，扩展极其容易，配合php-curlmulti的强大能力可以分分钟拷贝一个站。
1. <sub>所有页面的重复的url只会精确处理一次。
1. <sub>全自动处理任意格式url的相对路径绝对路径。
1. <sub>css中引入的背景图等资源全自动处理，css中的@import全自动处理，支持任意深度！
1. <sub>支持指定多个前缀url并且可以针对每个前缀url设置一个深度。
1. <sub>支持对每个前缀url指定二级前缀，每个二级前缀还可以设置一个深度。
1. <sub>全自动处理3xx跳转。
1. <sub>跨站资源共享，例如采集站点A的时候A用到了站点B的图片，jss，css等，等采集站点B的时候这些文件会直接使用不会再次下载。
1. <sub>同一个目录下可以复制任意数量的站点并且不会发生任何可能的文件重复或覆盖。
1. <sub>支持不同类型资源文件下载控制。

<sub>结果展示：http://manual.phpdr.net/

API(Core)
-------------------
```PHP
public $maxThread = 10
```
最大并发数，这个值可以运行中动态改变。<br>
最大数限制跟操作系统和libcurl有关，和本库无关。

```PHP
public $maxTry = 3
```
触发curl错误或用户错误之前最大重试次数，超过次数$cbFail指定的回调会被调用。

```PHP
public $opt = array ()
```
全局CURLOPT_\*，可以被add()中设置的opt覆盖。

```PHP
public $cache = array ('enable' => false, 'compress' => false, 'dir' => null, 'expire' =>86400, 'verifyPost' => false)
```
缓存选项很容易被理解，缓存使用url来识别。如果使用缓存类库不会访问网络而是直接返回缓存。

```PHP
public $taskPoolType = 'queue'
```
有两个值stack或queue，这两个选项决定任务池是深度优先还是广度优先，默认是stack深度优先。

```PHP
public $cbTask = null
```
当并发数小于$maxThread并且任务池为空的时候类库会调用$cbTask指定的回调函数。

```PHP
public $cbInfo = null
```
运行信息回调函数，回调中使用print_r()可以查看详细信息，回调函数最快1秒钟调用一次。

```PHP
public $cbUser = null
```
用户自定义回调函数，这个函数调用非常频繁，用户函数可以执行任何操作，有网络活动就会调用。

```PHP
public $cbFail = null
```
失败任务回调，可以被add()中指定的错误回调覆盖。

```PHP
public function add(array $item, $process = null, $fail = null, $ahead = null)
```
添加一个任务到任务池<br>
**$item['opt']=array()** 当前任务的CURLOPT_\*，覆盖全局的CURLOPT_\*。<br>
**$item['args']** 成功和失败回调的第二个参数。<br>
**$item['cache']=array()** 任务缓存配置，覆盖合并$cache属性。<br />
**$process** 任务成功完成调用此回调，回调的第一个参数是结果数组，第二个参数是$item['args']。可以返回一个数组，键：cache（bool,控制是否缓存本次请求）<br />
**$fail** 任务失败回调，第一个参数是相关信息，第二个参数是$item['args']。<br>
**$ahead** bool,是否优先执行任务。

```PHP
public function start()
```
开始回调循环，此方法是阻塞的。

API(Base)
-----------------
```PHP
function hashpath($name)
```
获得hash相对路径。

```PHP
function substr($str, $start, $end = null, $mode = 'g')
```
获取开始和结束字符串之间的字符串，开始和结束字符串不包含在内。

```PHP
function cbCurlFail($error, $args)
```
全局默认错误回调。

```PHP
function cbCurlInfo($info,$isFirst,$isLast)
```
默认的信息回调，以标准形式输出运行信息。

```PHP
function encoding($html, $in = null, $out = 'UTF-8', $mode = 'auto')
```
强力的转码函数，可以自动获取当前编码，转码后自动修改\<head\>\</head\>中的编码，可选不同的转码函数。

```PHP
function isUrl($str)
```
是否是一个绝对的url。

```PHP
function uri2url($uri, $urlCurrent)
```
根据当前页面url获取当前页中的相对uri对应的绝对url。

```PHP
function url2uri($url, $urlCurrent)
```
根据当前页面url获取当前页中的绝对url对应的相对uri。

```PHP
function urlDir($url)
```
绝对url对应的目录，参数中的url应该是重定向之后的url。

```PHP
function getCurl()
```
返回核心类的对象。

# lyadmin

## 项目介绍

lyadmin是一套轻量级通用后台，采用Bootstrap3制作，自带权限管理，模块化开发。

## 官网：

http://lyadmin.lingyun.net

## 后台截图

![lyadmin](http://of7audkb0.bkt.clouddn.com/lyadmin.png)

## 优秀特性

### 真正独家Builder页面自动生成

长久以来，以织梦CMS、帝国CMS为代表的一系列老牌CMS在模板标签的使用上可以说是炉火纯青，模板标签的出现让前端开发页面变得十分轻松，然而后端开发人员却一直没有优秀的开发模式帮助后端人员从前端页面中解脱出来，而Builder的出现就是为了解决这个问题。

从测试版本发布以来，我们一直在探索如何封装后台的页面生成以解放后端开发人员的生产力。

在传统的MVC分层设计中，开发者需要写MVC三层逻辑代码，而在ThinkPHP的开发中，M层实际上是可以不写的。不写的原因是ThinkPHP分封装了数据层，ORM模式简化了数据库操作，没有数据模型也很方便。

那么剩下的C层和V层中，我们通过研究发现在后台开发时，V层是一项非常费时费力却又包含重复劳动的开发，

再后来，CoreThink在1.0正式版本中终于推出自己Builder，并且用Builder重写了后台的全部页面（除了个别特殊页面）。我们认为程序的后台在早期对页面复杂度的要求停留在两个核心关键：表单＋列表。所以我们抽象出了对应的Builder分别为FormBuilder、Listbuilder。

简单来讲FormBuilder用来帮助后端开发自动生成表单页面，比如发布文章、编辑文章；而Listbuilder则用来帮助后端人员自动生成列表页面，比如用户列表、文章列表。可以说，Builder的出现必将WEB开发带入一个新的世纪。

### 积木式模块化

系统功能采用模块化、组件化、插件化等开放化低耦合设计，应用商城拥有丰富的功能模块、插件、主题，便于用户灵活扩展和二次开发。


### 多终端多平台支持
采用Bootstrap3精确定制的lyui除了拥有100%bootstrap体验外，融合了更多适合国人使用的前端组建。并且一套代码适应多种屏幕大小。


## 目录结构
```
├─index.php 入口文件
│
├─Addons 插件目录
├─Application 应用模块目录
│  ├─Admin 后台模块
│  │  ├─Conf 后台配置文件目录
│  │  ├─Common 后台函数目录
│  │  ├─Controller 后台控制器目录
│  │  ├─Model 后台模型目录
│  │  └─View 后台视图文件目录
│  │
│  ├─Common 公共模块目录（不能直接访问）
│  │  ├─Behavior 行为扩展目录
│  │  ├─Builder Builder目录
│  │  ├─Common 公共函数文件目录
│  │  ├─Conf 公共配置文件目录
│  │  ├─Controller 公共控制器目录
│  │  ├─Model 公共模型目录
│  │  └─Util 第三方类库目录
│  │
│  ├─Home 前台模块
│  │  ├─Conf 前台配置文件目录
│  │  ├─Common 前台函数目录
│  │  ├─Controller 前台控制器目录
│  │  ├─Model 前台模型目录
│  │  ├─TagLib 前台标签库目录
│  │  └─View 模块视图文件目录
│  │
│  ├─Install 安装模块
│  │  ├─Conf 配置文件目录
│  │  ├─Common 函数目录
│  │  ├─Controller 控制器目录
│  │  ├─Model 模型目录
│  │  └─View 模块视图文件目录
│  │
│  └─... 扩展的可装卸功能模块
│
├─Public 应用资源文件目录
│  ├─libs 第三方插件类库目录
│  ├─css gulp编译样式结果存放目录
│  └─js gulp编译脚本结果存放目录
│
├─Runtime 应用运行时目录
├─Framework 框架目录
└─Uploads 上传根目录
```

##问题反馈

在使用中有任何问题，欢迎反馈给我们，可以用以下联系方式跟我们交流

* 邮件: admin@lingyun.net
* QQ群: 252262604

##感激

感谢以下的项目,排名不分先后

* [Bootstrap](http://getbootstrap.com)
* [jQuery](http://jquery.com)
* [ThinkPHP](http://thinkphp.cn/)

##关于我们

南京科斯克网络科技有限公司

<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------

$_config = [
    /**
     * 根据零云用户协议：
     * 任何情况下使用零云(除了免费版lyadmin和CoreThink)均需获取官方授权，违者追究法律责任，授权联系：http://www.lingyun.net
     */
    'product_name'           => 'lingyun', // 产品名称
    'product_title'          => 'lyadmin', // 产品标题
    'logo_default'           => 'lyadmin', // 产品Logo
    'logo_inverse'           => 'lyadmin', // 产品Logo深色背景
    'current_version'        => '2.0.0', // 当前版本号
    'develop_version'        => 'beta2', // 开发版本号
    'build_version'          => '201703221500', // 编译标记
    'model_name'             => 'lyadmin', // 产品型号
    'model_title'            => '后台版', // 产品型号标题
    'website_domain'         => 'http://www.lingyun.net', // 官方网址
    'update_url'             => '/appstore/home/core/update', // 官方更新网址
    'team_title'             => '南京科斯克网络科技有限公司', // 公司名称
    'team_member'            => '江如意、赵瀚卿、张玥、潘美红、赵川', // 团队成员
    'team_address'           => '南京市鼓楼区广东路38号', // 公司地址
    'team_email'             => 'service@lingyun.net', // 公司邮箱
    'team_phone'             => '15005173785', // 公司电话
    'team_qq'                => '209216005', // 公司QQ
    'team_qqqun'             => '105108204', // 公司QQ群
    'auth_key'               => 'CoreThink', // 系统加密KEY，轻易不要修改此项，否则容易造成用户无法登录，如要修改，务必备份原key

    'data_crypt_type'        => 'Think',

    // 允许访问模块
    'module_allow_list'      => ['home', 'admin', 'install'],

    // 系统功能模板
    'user_center_side'       => APP_DIR . 'user/view/index/side.html',
    'user_center_info'       => APP_DIR . 'user/view/index/info.html',
    'user_center_form'       => APP_DIR . 'user/view/builder/form.html',
    'user_center_list'       => APP_DIR . 'user/view/builder/list.html',
    'home_public_layout'     => APP_DIR . 'home/view/public/layout.html',
    'admin_public_layout'    => APP_DIR . 'admin/view/public/layout.html',
    'home_public_modal'      => APP_DIR . 'home/view/public/modal.html',
    'listbuilder_layout'     => BUILDER_DIR . 'listbuilder.html',
    'formbuilder_layout'     => BUILDER_DIR . 'formbuilder.html',

    // 文件上传默认驱动
    'upload_driver'          => 'Local',

    // 文件上传相关配置
    'upload_config'          => array(
        'mimes'    => '', // 允许上传的文件MiMe类型
        'maxSize'  => 2 * 1024 * 1024, // 上传的文件大小限制 (0-不做限制，默认为2M，后台配置会覆盖此值)
        'autoSub'  => true, // 自动子目录保存文件
        'subName'  => array('date', 'Y-m-d'), // 子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => './uploads/', // 保存根路径
        'savePath' => '', // 保存路径
        'saveName' => array('uniqid', ''), // 上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'  => '', // 文件保存后缀，空则使用原后缀
        'replace'  => false, // 存在同名是否覆盖
        'hash'     => true, // 是否生成hash编码
        'callback' => false, // 检测文件是否存在回调函数，如果存在返回文件信息数组
    ),

    // +----------------------------------------------------------------------
    // | 应用设置(tp5内置)
    // +----------------------------------------------------------------------
    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 注册的根命名空间
    'root_namespace'         => [
        'lyf' => APP_DIR . 'common/util/lyf/',
    ],
    // 扩展配置文件
    'extra_config_list'      => ['route', 'validate'],
    // 扩展函数文件
    'extra_file_list'        => [APP_PATH . 'common/common/helper' . EXT, APP_PATH . 'common/common/function' . EXT],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 是否启用控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'home',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Empty',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => true,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如.thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'            => 'Think',
        // 模板路径
        'view_path'       => '',
        // 模板后缀
        'view_suffix'     => 'html',
        // 模板文件名分隔符
        'view_depr'       => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'       => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'         => '}',
        // 标签库标签开始标记
        'taglib_begin'    => '<',
        // 标签库标签结束标记
        'taglib_end'      => '>',
        // 预先加载的标签库
        'taglib_pre_load' => '\\app\\common\\taglib\\Lingyun',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => array(
        '__ROOT__'       => __ROOT__,
        '__PUBLIC__'     => \think\Request::instance()->domain() . __ROOT__ . '/public',
        '__LYUI__'       => \think\Request::instance()->domain() . __ROOT__ . '/public/libs/lyui/dist',
        '__ADMIN_IMG__'  => \think\Request::instance()->domain() . __ROOT__ . ltrim(APP_DIR, '.') . 'admin/view/public/img',
        '__ADMIN_CSS__'  => \think\Request::instance()->domain() . __ROOT__ . ltrim(APP_DIR, '.') . 'admin/view/public/css',
        '__ADMIN_JS__'   => \think\Request::instance()->domain() . __ROOT__ . ltrim(APP_DIR, '.') . 'admin/view/public/js',
        '__ADMIN_LIBS__' => \think\Request::instance()->domain() . __ROOT__ . ltrim(APP_DIR, '.') . 'admin/view/public/libs',
        '__HOME_IMG__'   => \think\Request::instance()->domain() . __ROOT__ . ltrim(APP_DIR, '.') . 'home/view/public/img',
        '__HOME_CSS__'   => \think\Request::instance()->domain() . __ROOT__ . ltrim(APP_DIR, '.') . 'home/view/public/css',
        '__HOME_JS__'    => \think\Request::instance()->domain() . __ROOT__ . ltrim(APP_DIR, '.') . 'home/view/public/js',
        '__HOME_LIBS__'  => \think\Request::instance()->domain() . __ROOT__ . ltrim(APP_DIR, '.') . 'home/view/public/libs',
    ),
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，支持 file socket
        'type' => 'File',
        // 日志保存目录
        'path' => LOG_PATH,
    ],

    // +----------------------------------------------------------------------
    // | Trace设置
    // +----------------------------------------------------------------------

    'trace'                  => [
        //支持Html Console
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

    'cache'                  => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => CACHE_PATH,
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'lingyun',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'p',
        'list_rows' => 15,
    ],

    // 验证码配置
    'captcha'                => [
        // 验证码字符集合
        'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        // 验证码字体大小(px)
        'fontSize' => 25,
        // 是否画混淆曲线
        'useCurve' => true,
        // 验证码图片高度
        'imageH'   => 30,
        // 验证码图片宽度
        'imageW'   => 100,
        // 验证码位数
        'length'   => 5,
        // 验证成功后是否重置
        'reset'    => true,
    ],
];

// 获取数据库配置信息，手动修改数据库配置请修改./Data/db.php，这里无需改动
if (is_file('./data/db.php')) {
    $db_config = include './data/db.php'; // 包含数据库连接配置
} else {
    // 开启开发部署模式
    if (@$_SERVER[ENV_PRE . 'DEV_MODE'] === 'true') {
        // 数据库配置
        $db_config = array(
            'database' => [
                // 数据库类型
                'type'           => @$_SERVER[ENV_PRE . 'DB_TYPE'] ?: 'mysql',
                // 数据库连接DSN配置
                'dsn'            => '',
                // 服务器地址
                'hostname'       => @$_SERVER[ENV_PRE . 'DB_HOST'] ?: '127.0.0.1',
                // 数据库名
                'database'       => @$_SERVER[ENV_PRE . 'DB_NAME'] ?: 'lingyun',
                // 数据库用户名
                'username'       => @$_SERVER[ENV_PRE . 'DB_USER'] ?: 'root',
                // 数据库密码
                'password'       => @$_SERVER[ENV_PRE . 'DB_PWD'] ?: '',
                // 数据库连接端口
                'hostport'       => @$_SERVER[ENV_PRE . 'DB_PORT'] ?: '3306',
                // 数据库连接参数
                'params'         => [],
                // 数据库编码默认采用utf8
                'charset'        => 'utf8',
                // 数据库表前缀
                'prefix'         => @$_SERVER[ENV_PRE . 'DB_PREFIX'] ?: 'ly_',
                // 数据库调试模式
                'debug'          => false,
                // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
                'deploy'         => 0,
                // 数据库读写是否分离 主从式有效
                'rw_separate'    => false,
                // 读写分离后 主服务器数量
                'master_num'     => 1,
                // 指定从服务器序号
                'slave_no'       => '',
                // 是否严格检查字段是否存在
                'fields_strict'  => true,
                // 自动写入时间戳字段
                'auto_timestamp' => false,
            ],
        );
    } else {
        // 数据库配置
        $db_config = array(
            'database' => [
                // 数据库类型
                'type'           => 'mysql',
                // 数据库连接DSN配置
                'dsn'            => '',
                // 服务器地址
                'hostname'       => '127.0.0.1',
                // 数据库名
                'database'       => 'lingyun',
                // 数据库用户名
                'username'       => 'root',
                // 数据库密码
                'password'       => '',
                // 数据库连接端口
                'hostport'       => '',
                // 数据库连接参数
                'params'         => [],
                // 数据库编码默认采用utf8
                'charset'        => 'utf8',
                // 数据库表前缀
                'prefix'         => 'ly_',
                // 数据库调试模式
                'debug'          => false,
                // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
                'deploy'         => 0,
                // 数据库读写是否分离 主从式有效
                'rw_separate'    => false,
                // 读写分离后 主服务器数量
                'master_num'     => 1,
                // 指定从服务器序号
                'slave_no'       => '',
                // 是否严格检查字段是否存在
                'fields_strict'  => true,
                // 自动写入时间戳字段
                'auto_timestamp' => false,
            ],
        );
    }
}

// 返回合并的配置
return array_merge(
    $_config, // 系统全局默认配置
    $db_config, // 数据库配置数组
    include BUILDER_DIR . 'config.php' // 包含Builder配置
);

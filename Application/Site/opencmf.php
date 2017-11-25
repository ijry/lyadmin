<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
// | 版权申明：零云不是一个自由软件，是零云官方推出的商业源码，严禁在未经许可的情况下
// | 拷贝、复制、传播、使用零云的任意代码，如有违反，请立即删除，否则您将面临承担相应
// | 法律责任的风险。如果需要取得官方授权，请联系官方http://www.lingyun.net
// +----------------------------------------------------------------------
// 模块信息配置
return array(
    // 模块信息
    'info'       => array(
        'name'        => 'Site',
        'title'       => '站点',
        'icon'        => 'fa fa-at',
        'icon_color'  => '#8EF79D',
        'description' => '独立站群模块',
        'developer'   => '南京科斯克网络科技有限公司',
        'website'     => 'http://www.lingyun.net',
        'version'     => '1.0.0',
        'dependences' => array(
            'Admin' => '1.2.0',
        ),
    ),

    // 模块配置
    'config'     => array(
        'title'         => array(
            'title' => '网站名称',
            'type'  => 'text',
            'value' => '',
            'tip'   => '填写您的网站名称',
            'value' => '零云建站',
        ),
        'logo'          => array(
            'title' => '网站logo',
            'type'  => 'picture',
            'value' => '',
            'tip'   => '上传您的网站logo，最好200K以内',
            'value' => '__APP_DIR__Home/View/Public/img/default/logo_title.png',
        ),
        'theme'         => array(
            'title' => '当前主题',
            'type'  => 'text',
            'value' => '',
            'tip'   => '',
            'value' => '1',
        ),
        'company'       => array(
            'title' => '公司名称',
            'type'  => 'text',
            'value' => '',
            'tip'   => '填写您的公司名称',
            'value' => '南京科斯克网络科技有限公司',
        ),
        'email'         => array(
            'title' => '联系邮箱',
            'type'  => 'text',
            'value' => '',
            'tip'   => '填写您的常用邮箱',
            'value' => 'admin@lingyun.net',
        ),
        'phone'         => array(
            'title' => '联系电话',
            'type'  => 'text',
            'value' => '',
            'tip'   => '填写您的常用电话',
            'value' => '18185882821',
        ),
        'address'       => array(
            'title' => '公司地址',
            'type'  => 'text',
            'value' => '',
            'tip'   => '填写您的公司地址',
            'value' => '南京市鼓楼区广东路38号',
        ),
        'qq'            => array(
            'title' => 'QQ',
            'type'  => 'text',
            'value' => '',
            'tip'   => 'QQ',
            'value' => '209216005',
        ),
        'qq_qun'        => array(
            'title' => 'QQ群',
            'type'  => 'text',
            'value' => '',
            'tip'   => 'QQ群',
        ),
        'qr_code'       => array(
            'title' => '二维码',
            'type'  => 'picture',
            'value' => '',
            'tip'   => '二维码',
        ),
        'qr_weixin'     => array(
            'title' => '微信二维码',
            'type'  => 'picture',
            'value' => '',
            'tip'   => '微信二维码',
        ),
        'qr_ios'        => array(
            'title' => 'IOS二维码',
            'type'  => 'picture',
            'value' => '',
            'tip'   => 'IOS二维码',
        ),
        'qr_android'    => array(
            'title' => '安卓二维码',
            'type'  => 'picture',
            'value' => '',
            'tip'   => '安卓二维码',
        ),
        'description'   => array(
            'title' => '站点描述',
            'type'  => 'textarea',
            'value' => '',
            'tip'   => '站点描述',
        ),
        'keywords'      => array(
            'title' => '网站关键字',
            'type'  => 'textarea',
            'value' => '',
            'tip'   => '网站关键字',
        ),
        'icp'           => array(
            'title' => 'ICP备案',
            'type'  => 'text',
            'value' => '',
            'tip'   => 'ICP备案',
            'value' => '苏ICP备15020094号',
        ),
        'upload_driver' => array(
            'title'   => '站群上传驱动',
            'type'    => 'select',
            'options' => array(
                'Local'   => '本地',
                'Qiniu'   => '七牛云',
                'Upyun'   => '又拍云',
                'Sae'     => '新浪Sae',
                'Bcs'     => '百度云',
                'Tietuku' => '贴图库',
                'Ftp'     => 'FTP',
            ),
            'value'   => 'Local',
        ),
    ),

    // 后台菜单及权限节点配置
    'admin_menu' => array(
        '1'  => array(
            'pid'   => '0',
            'title' => '站点',
            'icon'  => 'fa fa-at',
        ),
        '2'  => array(
            'pid'   => '1',
            'title' => '网站管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '3'  => array(
            'pid'   => '2',
            'title' => '网站设置',
            'icon'  => 'fa fa-wrench',
            'url'   => 'Site/Index/module_config',
        ),
        '4'  => array(
            'pid'   => '2',
            'title' => '概况',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Index/index',
        ),
        '5'  => array(
            'pid'   => '4',
            'title' => '自定义模板配置',
            'url'   => 'Site/Index/theme_config',
        ),
        '10' => array(
            'pid'   => '2',
            'title' => '首页幻灯',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Slider/slider',
        ),
        '11' => array(
            'pid'   => '10',
            'title' => '新增幻灯',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Slider/slider_add',
        ),
        '12' => array(
            'pid'   => '10',
            'title' => '编辑幻灯',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Slider/slider_edit',
        ),
        '15' => array(
            'pid'   => '2',
            'title' => '分类管理',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Category/category',
        ),
        '16' => array(
            'pid'   => '15',
            'title' => '新增分类',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Category/category_add',
        ),
        '17' => array(
            'pid'   => '15',
            'title' => '编辑分类',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Category/category_edit',
        ),
        '20' => array(
            'pid'   => '2',
            'title' => '文章管理',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Article/article',
        ),
        '21' => array(
            'pid'   => '20',
            'title' => '新增文章',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Article/article_add',
        ),
        '22' => array(
            'pid'   => '20',
            'title' => '编辑文章',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Article/article_edit',
        ),
        '25' => array(
            'pid'   => '2',
            'title' => '友情链接',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Flink/flink',
        ),
        '26' => array(
            'pid'   => '25',
            'title' => '新增链接',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Flink/flink_add',
        ),
        '27' => array(
            'pid'   => '25',
            'title' => '编辑链接',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Flink/flink_edit',
        ),
        '30' => array(
            'pid'   => '2',
            'title' => '留言管理',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Liuyan/liuyan',
        ),
        '35' => array(
            'pid'   => '2',
            'title' => '自定义表单',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Form/Form',
        ),
        '36' => array(
            'pid'   => '25',
            'title' => '新增表单',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Form/flink_add',
        ),
        '37' => array(
            'pid'   => '25',
            'title' => '编辑表单',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Form/flink_edit',
        ),
        '38' => array(
            'pid'   => '25',
            'title' => '编辑字段',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Field/field',
        ),
        '39' => array(
            'pid'   => '25',
            'title' => '新增字段',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Field/field_add',
        ),
        '40' => array(
            'pid'   => '25',
            'title' => '编辑字段',
            'icon'  => 'fa fa-list',
            'url'   => 'Site/Field/field_edit',
        ),
    ),
);

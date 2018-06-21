CREATE TABLE `ly_site_theme_cate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父分类ID',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '分类标题',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='主题分类表';

INSERT INTO `ly_site_theme_cate` (`id`, `pid`, `title`, `create_time`, `update_time`, `sort`, `status`) VALUES (1, 0, '默认分类', 0, 0, 0, 1);

CREATE TABLE `ly_site_theme` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主题ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `cid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '主题名称',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '主题标题',
  `description` varchar(127) NOT NULL DEFAULT '' COMMENT '描述',
  `developer` varchar(32) NOT NULL DEFAULT '' COMMENT '开发者',
  `version` varchar(8) NOT NULL DEFAULT '' COMMENT '版本',
  `price` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `is_public` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否公共模板',
  `buy_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买次数',
  `demo_url` VARCHAR(255) NULL DEFAULT '' COMMENT '演示地址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='主题模板';

CREATE TABLE `ly_site_ad` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(63) NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(63) NOT NULL DEFAULT '' COMMENT '标题',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `value` text NOT NULL COMMENT '广告内容',
  `url` text NOT NULL COMMENT '链接',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广告表';

CREATE TABLE `ly_site_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `out_trade_no` varchar(128) NOT NULL DEFAULT '' COMMENT '订单号',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `site_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `theme_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `price` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `is_pay` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付标记',
  `is_callback` text NULL COMMENT '回调标记',
  `expire` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `config` longtext NULL COMMENT '模板配置',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='模板订单表';

CREATE TABLE `ly_site_index` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` INT(11) NOT NULL DEFAULT '0' COMMENT 'UID',
  `title` VARCHAR(127) NOT NULL DEFAULT '' COMMENT '标题',
  `logo` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'logo',
  `domain` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '二级域名',
  `theme` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '当前主题',
  `company` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '公司名称',
  `email` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '公司邮箱',
  `phone` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '联系电话',
  `address` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '公司地址',
  `qr_android` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '安卓二维码',
  `qr_ios` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '苹果二维码',
  `qr_code` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '二维码',
  `qr_weixin` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '微信公众号',
  `qr_weixin_app` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '微信小程序',
  `qq` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'QQ',
  `qq_qun` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'QQ群',
  `weibo` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '微博',
  `description` TEXT NULL COMMENT '站点描述',
  `keywords` TEXT NULL COMMENT '关键字',
  `site_statics` TEXT NULL COMMENT '站点统计',
  `icp` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'ICP备案',
  `view_count` INT(11) NOT NULL DEFAULT '0' COMMENT '访问次数',
  `create_time` INT(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` INT(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` TINYINT(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='网站列表';
INSERT INTO `ly_site_index` (`id`, `uid`, `title`, `logo`, `domain`, `theme`, `company`, `email`, `phone`, `address`, `qr_android`, `qr_ios`, `qr_code`, `qr_weixin`, `qr_weixin_app`, `qq`, `qq_qun`, `weibo`, `description`, `keywords`, `site_statics`, `icp`, `view_count`, `create_time`, `update_time`, `status`)
VALUES
  (1, 1, '零云', '__APP_DIR__Home/View/Public/img/default/logo_title.png', '', '1', '南京科斯网络科技有限公司', 'admin@lingyun.net', '025-66048939', '南京市鼓楼区广东路38号', '__APP_DIR__Home/View/Public/img/default/qr_android.png', '__APP_DIR__Home/View/Public/img/default/qr_ios.png', '__APP_DIR__Home/View/Public/img/default/qr_code.png', '__APP_DIR__Home/View/Public/img/default/qr_weixin.png', '__APP_DIR__Home/View/Public/img/default/qr_weixin_app.png', '209216005', '130747567', 'Nanjing', '零云是一套国内领先的互联网积木式开发云平台，追求简单、高效、卓越。可轻松实现支持多终端的互联网产品快速搭建、部署、上线。系统功能采用模块化、组件化、插件化等开放化低耦合设计，应用商城拥有丰富的功能模块、插件、主题，便于用户灵活扩展和二次开发。', '零云、新一代云平台、微信小程序开发、微信应用号开发、零云网络', NULL, '苏ICP备15020094号', 257, 1526973334, 1526973334, 1);


CREATE TABLE `ly_site_slider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '幻灯ID',
  `site_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '标题',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '点击链接',
  `target` varchar(11) NOT NULL DEFAULT '' COMMENT '新窗口打开',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='幻灯切换表';

CREATE TABLE `ly_site_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `site_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `cate_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类类型',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父分类ID',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '分类标题',
  `content` text NULL COMMENT '分类单页内容',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '外链',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
  `banner` varchar(255) NOT NULL DEFAULT '' COMMENT 'Banner图',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `lists_template` varchar(255) NOT NULL DEFAULT '' COMMENT '列表模版',
  `detail_template` varchar(255) NOT NULL DEFAULT '' COMMENT '详情模版',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章分类表';

CREATE TABLE `ly_site_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `site_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '网站ID',
  `cid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '文章标题',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
  `banner` varchar(255) NOT NULL DEFAULT '' COMMENT '幻灯切换专用图',
  `abstract` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `content` text NULL COMMENT '文章内容',
  `tags` varchar(127) NOT NULL DEFAULT '' COMMENT '标签',
  `source_id` int(11) NOT NULL DEFAULT '0' COMMENT '文章来源',
  `comment_count` int(11) NOT NULL DEFAULT '0' COMMENT '评论次数',
  `view_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '阅读次数',
  `detail_template` varchar(255) NOT NULL DEFAULT '' COMMENT '详情页模版',
  `is_recommend` int(11) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章表';

CREATE TABLE `ly_site_flink` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '友链名称',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '友链logo',
  `url` text NUll COMMENT '友链链接',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='友情链接表';

CREATE TABLE `ly_site_liuyan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '留言ID',
  `site_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '姓名',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '电话',
  `content` text NULL COMMENT '内容',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='留言表';

CREATE TABLE `ly_site_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论父ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `data_id` int(11) NOT NULL COMMENT '数据ID',
  `content` text NOT NULL COMMENT '评论内容',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '来源IP',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='文档评论表';

CREATE TABLE `ly_site_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表单id',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '网站ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '表单标题',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='表单列表';

CREATE TABLE `ly_site_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '字段主键',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '表单外键',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '字段name',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '字段类型 text之类的',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '字段标题',
  `hint` varchar(255) NOT NULL DEFAULT '' COMMENT '字段提示',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `choose` varchar(255) NOT NULL DEFAULT '' COMMENT '当选择多选单选时用来选择的值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='字段列表';

CREATE TABLE `ly_site_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '来自表单的标题',
  `data` varchar(255) NOT NULL DEFAULT '' COMMENT 'json格式的数据',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='数据列表';

CREATE TABLE `ly_site_source` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点ID',
  `source` varchar(255) NOT NULL DEFAULT '' COMMENT '文章来源',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '来源网址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) DEFAULT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='文章来源表';

INSERT INTO `ly_site_source` (`id`, `site_id`, `source`, `url`, `create_time`, `update_time`, `sort`, `status`)
VALUES
  (1, 0, '原创', '', 1519788133, 1519788600, 1, 1);


CREATE TABLE `ly_site_theme` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主题ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `cid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '主题名称',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '主题标题',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
  `images` text COMMENT '图片预览',
  `config` LONGTEXT NULL COMMENT '模板配置',
  `view_count` INT(11) NOT NULL DEFAULT '0' COMMENT '访问次数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='主题模板';

INSERT INTO `ly_site_theme` (`id`, `uid`, `cid`, `name`, `title`, `cover`, `images`, `config`, `view_count`, `create_time`, `update_time`, `sort`, `status`)
VALUES
  (1, 1, 1, 'default', '默认主题', '__APP_DIR__Site/View/Public/img/theme_default.png', '', '{\"index_news_cid\":\"2\",\"index_product_cid\":\"3\"}', 48, 0, 0, 0, 1);

CREATE TABLE `ly_site_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `cate_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类类型',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父分类ID',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '分类标题',
  `content` text COMMENT '分类单页内容',
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

INSERT INTO `ly_site_category` (`id`, `cate_type`, `pid`, `title`, `content`, `cover`, `is_show`, `create_time`, `update_time`, `sort`, `status`) VALUES (1, 0, 0, '公司简介', '', '', 1, 0, 0, 0, 1);
INSERT INTO `ly_site_category` (`id`, `cate_type`, `pid`, `title`, `content`, `cover`, `is_show`, `create_time`, `update_time`, `sort`, `status`) VALUES (2, 0, 0, '新闻中心', '', '', 1, 0, 0, 0, 1);
INSERT INTO `ly_site_category` (`id`, `cate_type`, `pid`, `title`, `content`, `cover`, `is_show`, `create_time`, `update_time`, `sort`, `status`) VALUES (3, 0, 0, '产品中心', '', '', 1, 0, 0, 0, 1);
INSERT INTO `ly_site_category` (`id`, `cate_type`, `pid`, `title`, `content`, `cover`, `is_show`, `create_time`, `update_time`, `sort`, `status`) VALUES (4, 0, 0, '合作案例', '', '', 1, 0, 0, 0, 1);
INSERT INTO `ly_site_category` (`id`, `cate_type`, `pid`, `title`, `content`, `cover`, `is_show`, `create_time`, `update_time`, `sort`, `status`) VALUES (5, 0, 0, '联系我们', '', '', 1, 0, 0, 0, 1);

CREATE TABLE `ly_site_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `cid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '文章标题',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
  `cover_slider` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图？？？',
  `banner` varchar(255) NOT NULL DEFAULT '' COMMENT '幻灯切换专用图',
  `abstract` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '简介',
  `content` text COMMENT '文章内容',
  `view_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '阅读次数',
  `detail_template` varchar(255) NOT NULL DEFAULT '' COMMENT '详情页模版',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章表';

INSERT INTO `ly_site_article` (`id`, `uid`, `cid`, `title`, `cover`, `cover_slider`, `banner`, `abstract`, `content`, `view_count`, `detail_template`, `create_time`, `update_time`, `sort`, `status`)
VALUES
  (1, 1, 2, '测试文章1', '__APP_DIR__Site/View/Public/img/product1.png', '__APP_DIR__Site/View/Public/img/slider.png', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1),
  (2, 1, 2, '测试文章2', '__APP_DIR__Site/View/Public/img/product1.png', '', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1),
  (3, 1, 2, '测试文章3', '__APP_DIR__Site/View/Public/img/product2.png', '', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1),
  (4, 1, 2, '测试文章4', '__APP_DIR__Site/View/Public/img/product3.png', '', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1),
  (5, 1, 2, '测试文章5', '__APP_DIR__Site/View/Public/img/product4.png', '', '', '', '测试文章123', 1, '', 1500028451, 0, 0, 1),
  (6, 1, 2, '测试文章6', '__APP_DIR__Site/View/Public/img/product5.png', '', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1),
  (7, 1, 3, '测试文章7', '__APP_DIR__Site/View/Public/img/product6.png', '', '', '', '测试文章123', 1, '', 1500028451, 0, 0, 1),
  (8, 1, 3, '测试文章8', '__APP_DIR__Site/View/Public/img/product6.png', '', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1),
  (9, 1, 3, '测试文章9', '__APP_DIR__Site/View/Public/img/product6.png', '', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1),
  (10, 1, 3, '测试文章10', '__APP_DIR__Site/View/Public/img/product6.png', '', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1),
  (11, 1, 3, '测试文章11', '__APP_DIR__Site/View/Public/img/product6.png', '', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1),
  (12, 1, 3, '测试文章11', '__APP_DIR__Site/View/Public/img/product6.png', '', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1);

CREATE TABLE `ly_site_flink` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '友链名称',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '友链logo',
  `url` text COMMENT '友链链接',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='友情链接表';

CREATE TABLE `ly_site_slider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '幻灯ID',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '标题',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '点击链接',
  `target` varchar(11) NOT NULL DEFAULT '' COMMENT '新窗口打开',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1004 COMMENT='幻灯切换表';

INSERT INTO `ly_site_slider` (`id`, `title`, `cover`, `url`, `target`, `create_time`, `update_time`, `sort`, `status`) VALUES (1, '默认Banner', '__APP_DIR__Site/View/Public/img/slider1.jpg', '', '_blank', 0, 1501061741, 0, 1);
INSERT INTO `ly_site_slider` (`id`, `title`, `cover`, `url`, `target`, `create_time`, `update_time`, `sort`, `status`) VALUES (2, '默认Banner', '__APP_DIR__Site/View/Public/img/slider2.jpg', '', '_blank', 0, 1501061741, 0, 1);
INSERT INTO `ly_site_slider` (`id`, `title`, `cover`, `url`, `target`, `create_time`, `update_time`, `sort`, `status`) VALUES (3, '默认Banner', '__APP_DIR__Site/View/Public/img/slider3.jpg', '', '_blank', 0, 1501061741, 0, 1);

CREATE TABLE `ly_site_liuyan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '幻灯ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '姓名',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '电话',
  `content` text COMMENT '内容',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='留言表';


CREATE TABLE `ly_site_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表单id',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL COMMENT '表单标题',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='表单列表';

-- ----------------------------
-- Records of ly_site_form
-- ----------------------------
INSERT INTO `ly_site_form` VALUES ('1', '1504520124', '1504520124', '1', '示例表单');

CREATE TABLE `ly_site_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '字段主键',
  `fid` int(11) NOT NULL COMMENT '表单外键',
  `name` varchar(255) NOT NULL COMMENT '字段name',
  `type` varchar(255) NOT NULL COMMENT '字段类型 text之类的',
  `title` varchar(255) NOT NULL COMMENT '字段标题',
  `hint` varchar(255) NOT NULL COMMENT '字段提示',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `choose` varchar(255) NOT NULL COMMENT '当选择多选单选时用来选择的值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='字段列表';

-- ----------------------------
-- Records of ly_site_field
-- ----------------------------
INSERT INTO `ly_site_field` VALUES ('1', '1', '名字', 'text', '姓名', '你的姓名', '1504601168', '1504601168', '1', '');
INSERT INTO `ly_site_field` VALUES ('2', '1', '年龄', 'text', '年龄', '你的实际年龄', '1504601177', '1504601177', '1', '');
INSERT INTO `ly_site_field` VALUES ('3', '1', '住址', 'text', '户籍', '输入户籍', '1504601188', '1504601188', '1', '');
INSERT INTO `ly_site_field` VALUES ('4', '1', '性别', 'radio', '请选择性别', '请选择性别', '1504596019', '1504596019', '1', '男/女/保密');
INSERT INTO `ly_site_field` VALUES ('5', '1', '爱好', 'checkbox', '爱好', '爱好', '1504599321', '1504599321', '1', '游泳/跑步/篮球/足球');
INSERT INTO `ly_site_field` VALUES ('6', '1', '职业', 'select', '职业', '职业', '1504604858', '1504604858', '1', '学生/蓝领/白领/公务员');

CREATE TABLE `ly_site_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL COMMENT '来自表单的标题',
  `data` varchar(255) NOT NULL COMMENT 'json格式的数据',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='数据列表';

-- ----------------------------
-- Records of ly_site_data
-- ----------------------------
INSERT INTO `ly_site_data` VALUES ('1','1', '{\"\\u540d\\u5b57\":\"\\u8001\\u738b\",\"\\u5e74\\u9f84\":\"23\",\"\\u4f4f\\u5740\":\"\\u5357\\u4eac\",\"\\u6027\\u522b\":\"\\u7537\",\"\\u7231\\u597d\":\"\\u8dd1\\u6b65\",\"\\u804c\\u4e1a\":\"\\u516c\\u52a1\\u5458\"}', '1504608576', '1504608576', '1');

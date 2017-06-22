CREATE TABLE `ly_addon_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT 'logo',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接',
  `type` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='友情链接表';

INSERT INTO `ly_addon_link` (`id`, `title`, `logo`, `url`, `type`, `create_time`, `update_time`, `sort`, `status`)
VALUES
  (1, '青云', '/addon/Link/img/qingcloud.png', 'http://qingcloud.com', 2, 1481175884, 1481175884, 0, 1),
  (2, '氪空间', '/addon/Link/img/krspace.png', 'http://krspace.cn', 2, 1481175884, 1481175884, 0, 1),
  (3, '阿里云', '/addon/Link/img/aliyun.png', 'http://aliyun.com', 2, 1481175884, 1481175884, 0, 1),
  (4, 'Coding', '/addon/Link/img/coding.png', 'http://coding.net', 2, 1481175884, 1481175884, 0, 1),
  (5, '七牛云', '/addon/Link/img/qiniu.png', 'http://qiniu.com', 2, 1481175884, 1481175884, 0, 1),
  (6, 'Ucloud', '/addon/Link/img/ucloud.png', 'http://ucloud.cn', 2, 1481175884, 1481175884, 0, 1),
  (7, '开源中国', '/addon/Link/img/oschina.png', 'http://oschina.net', 2, 1481175884, 1481175884, 0, 1),
  (8, '极光推送', '/addon/Link/img/jiguang.png', 'http://jiguang.cn', 2, 1481175884, 1481175884, 0, 1),
  (9, 'Bootstrap中文网', '', 'http://www.bootcss.com', 1, 1481175884, 1481175884, 0, 1),
  (10, '猿团', '', 'http://www.yuantuan.com', 1, 1481175884, 1481175884, 0, 1),
  (11, '36氪', '', 'http://36kr.com', 1, 1481175884, 1481175884, 0, 1),
  (12, '程序员客栈', '', 'http://www.proginn.com', 1, 1481175884, 1481175884, 0, 1),
  (13, 'Leangoo敏捷协作工具', '', 'http://www.leangoo.com', 1, 1481175884, 1481175884, 0, 1),
  (14, '百度软件开放平台', '', 'http://open.rj.baidu.com', 1, 1481175884, 1481175884, 0, 1),
  (15, '快递100', '', 'http://kuaidi100.com', 1, 1481175884, 1481175884, 0, 1),
  (16, 'Framework7', '', 'http://framework7.taobao.org', 1, 1481175884, 1481175884, 0, 1),
  (17, 'Cordova', '', 'http://cordova.apache.org', 1, 1481175884, 1481175884, 0, 1),
  (18, 'ThinkPHP', '', 'http://thinkphp.cn', 1, 1481175884, 1481175884, 0, 1);

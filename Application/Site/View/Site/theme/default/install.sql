/* 轮播图 */
LOCK TABLES `ly_[sql_module_name]_slider` WRITE;

INSERT INTO `ly_[sql_module_name]_slider` (`site_id`, `title`, `cover`, `url`, `target`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_site_id], '默认Banner', '__APP_DIR__[module_name]/View/Public/img/slider1.jpg', '', '_blank', 0, 1501061741, 0, 1);
INSERT INTO `ly_[sql_module_name]_slider` (`site_id`, `title`, `cover`, `url`, `target`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_site_id], '默认Banner', '__APP_DIR__[module_name]/View/Public/img/slider2.jpg', '', '_blank', 0, 1501061741, 0, 1);
INSERT INTO `ly_[sql_module_name]_slider` (`site_id`, `title`, `cover`, `url`, `target`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_site_id], '默认Banner', '__APP_DIR__[module_name]/View/Public/img/slider3.jpg', '', '_blank', 0, 1501061741, 0, 1);

UNLOCK TABLES;


/* 分类 */
LOCK TABLES `ly_[sql_module_name]_category` WRITE;

INSERT INTO `ly_[sql_module_name]_category` (`site_id`, `cate_type`, `pid`, `title`, `content`, `url`, `cover`, `banner`, `is_show`, `lists_template`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_site_id], 0, 0, '公司简介', '', '', '', '', 1, '', '', 0, 0, 0, 1);
set @sql_cate_id1=(select @@IDENTITY);
INSERT INTO `ly_[sql_module_name]_category` (`site_id`, `cate_type`, `pid`, `title`, `content`, `url`, `cover`, `banner`, `is_show`, `lists_template`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_site_id], 0, 0, '新闻中心', '', '', '', '', 1, '', '', 0, 0, 0, 1);
set @sql_cate_id2=(select @@IDENTITY);
INSERT INTO `ly_[sql_module_name]_category` (`site_id`, `cate_type`, `pid`, `title`, `content`, `url`, `cover`, `banner`, `is_show`, `lists_template`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_site_id], 0, 0, '合作案例', '', '', '', '', 1, '', '', 0, 0, 0, 1);
set @sql_cate_id3=(select @@IDENTITY);
INSERT INTO `ly_[sql_module_name]_category` (`site_id`, `cate_type`, `pid`, `title`, `content`, `url`, `cover`, `banner`, `is_show`, `lists_template`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_site_id], 0, 0, '常见问题', '', '', '', '', 1, '', '', 0, 0, 0, 1);
set @sql_cate_id5=(select @@IDENTITY);
INSERT INTO `ly_[sql_module_name]_category` (`site_id`, `cate_type`, `pid`, `title`, `content`, `url`, `cover`, `banner`, `is_show`, `lists_template`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_site_id], 0, 0, '联系我们', '', '', '', '', 1, '', '', 0, 0, 0, 1);
set @sql_cate_id5=(select @@IDENTITY);

UNLOCK TABLES;


/* 文章 */
LOCK TABLES `ly_[sql_module_name]_article` WRITE;

INSERT INTO `ly_[sql_module_name]_article` (`uid`, `site_id`, `cid`, `title`, `cover`, `banner`, `abstract`, `content`, `view_count`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_uid], [sql_site_id], @sql_cate_id2, '测试文章1', '', '__APP_DIR__[module_name]/View/Public/img/slider.png', '', '测试文章123', 0, '', 1500028451, 0, 0, 1);
INSERT INTO `ly_[sql_module_name]_article` (`uid`, `site_id`, `cid`, `title`, `cover`, `banner`, `abstract`, `content`, `view_count`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_uid], [sql_site_id], @sql_cate_id2, '测试文章2', '__APP_DIR__[module_name]/View/Public/img/product1.png', '', '', '测试文章123', 3, '', 1500028451, 0, 0, 1);
INSERT INTO `ly_[sql_module_name]_article` (`uid`, `site_id`, `cid`, `title`, `cover`, `banner`, `abstract`, `content`, `view_count`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_uid], [sql_site_id], @sql_cate_id2, '测试文章3', '__APP_DIR__[module_name]/View/Public/img/product2.png', '', '', '测试文章123', 1, '', 1500028451, 0, 0, 1);
INSERT INTO `ly_[sql_module_name]_article` (`uid`, `site_id`, `cid`, `title`, `cover`, `banner`, `abstract`, `content`, `view_count`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_uid], [sql_site_id], @sql_cate_id2, '测试文章4', '__APP_DIR__[module_name]/View/Public/img/product3.png', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1);
INSERT INTO `ly_[sql_module_name]_article` (`uid`, `site_id`, `cid`, `title`, `cover`, `banner`, `abstract`, `content`, `view_count`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_uid], [sql_site_id], @sql_cate_id2, '测试文章5', '__APP_DIR__[module_name]/View/Public/img/product4.png', '', '', '测试文章123', 1, '', 1500028451, 0, 0, 1);
INSERT INTO `ly_[sql_module_name]_article` (`uid`, `site_id`, `cid`, `title`, `cover`, `banner`, `abstract`, `content`, `view_count`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_uid], [sql_site_id], @sql_cate_id2, '测试文章6', '__APP_DIR__[module_name]/View/Public/img/product5.png', '', '', '测试文章123', 1, '', 1500028451, 0, 0, 1);
INSERT INTO `ly_[sql_module_name]_article` (`uid`, `site_id`, `cid`, `title`, `cover`, `banner`, `abstract`, `content`, `view_count`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_uid], [sql_site_id], @sql_cate_id2, '测试文章7', '__APP_DIR__[module_name]/View/Public/img/product6.png', '', '', '测试文章123', 1, '', 1500028451, 0, 0, 1);
INSERT INTO `ly_[sql_module_name]_article` (`uid`, `site_id`, `cid`, `title`, `cover`, `banner`, `abstract`, `content`, `view_count`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_uid], [sql_site_id], @sql_cate_id2, '测试文章8', '', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1);
INSERT INTO `ly_[sql_module_name]_article` (`uid`, `site_id`, `cid`, `title`, `cover`, `banner`, `abstract`, `content`, `view_count`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_uid], [sql_site_id], @sql_cate_id2, '测试文章9', '', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1);
INSERT INTO `ly_[sql_module_name]_article` (`uid`, `site_id`, `cid`, `title`, `cover`, `banner`, `abstract`, `content`, `view_count`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_uid], [sql_site_id], @sql_cate_id2, '测试文章10', '', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1);
INSERT INTO `ly_[sql_module_name]_article` (`uid`, `site_id`, `cid`, `title`, `cover`, `banner`, `abstract`, `content`, `view_count`, `detail_template`, `create_time`, `update_time`, `sort`, `status`) VALUES ([sql_uid], [sql_site_id], @sql_cate_id2, '测试文章11', '', '', '', '测试文章123', 0, '', 1500028451, 0, 0, 1);

UNLOCK TABLES;


/* 网站设置 */
UPDATE `ly_[sql_module_name]_index` SET logo = '__APP_DIR__Home/View/Public/img/default/logo_title.png',
company = '南京科斯网络科技有限公司',
email = 'admin@lingyun.net',
phone = '025-66048939',
address = '南京市鼓楼区广东路38号',
qr_android = '__APP_DIR__Home/View/Public/img/default/qr_android.png',
qr_ios = '__APP_DIR__Home/View/Public/img/default/qr_ios.png',
qr_code = '__APP_DIR__Home/View/Public/img/default/qr_code.png',
qr_weixin = '__APP_DIR__Home/View/Public/img/default/qr_weixin.png',
qr_weixin_app = '__APP_DIR__Home/View/Public/img/default/qr_weixin_app.png',
qq = '209216005',
qq_qun = '130747567',
weibo = 'Nanjing',
description = '零云是一套国内领先的互联网积木式开发云平台，追求简单、高效、卓越。可轻松实现支持多终端的互联网产品快速搭建、部署、上线。系统功能采用模块化、组件化、插件化等开放化低耦合设计，应用商城拥有丰富的功能模块、插件、主题，便于用户灵活扩展和二次开发。',
keywords = '零云、新一代云平台、微信小程序开发、微信应用号开发、零云网络',
icp = '苏ICP备15020094号'
WHERE id = '[sql_site_id]';


/* 模板设置 */
set @config = CONCAT('{"index_news_cid": ', @sql_cate_id2, ',"index_product_cid": ', @sql_cate_id2, '}');
UPDATE `ly_[sql_module_name]_order` SET config = @config where site_id = '[sql_site_id]' AND theme_id = '[sql_theme_id]';

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
namespace Site\Controller;

use Home\Controller\HomeController;
/**
 * 站点控制器
 * 该控制器需要结合TP的域名部署绑定从而支持站群模块每个网站绑定自己的域名
 * @author jry <598821125@qq.com>
 */
class IndexController extends HomeController
{
    // 网站配置信息
    protected $info = array();

    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize()
    {
        // 父类初始化方法
        parent::_initialize();

        // 设置模板后缀
        C('TMPL_TEMPLATE_SUFFIX', '.htm');

        // 网站配置
        $con           = array();
        $con['status'] = '1';
        $con['id']     = 1;
        $info          = D('Index')->where($con)->find();
        if (!$info) {
            $this->error('站点不存在或已禁用');
        }
        $this->info = $info;

        // 增加访问次数
        $con       = array();
        $con['id'] = 1;
        D('Index')->where($con)->setInc('view_count');
    }

    /**
     * 首页
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 网站配置
        $info = $this->info;

        // 获取幻灯片列表
        $slider_model = D('Site/Slider');
        $con          = array();
        $slider_list  = $slider_model->where($con)->limit(10)->order('sort desc, id desc')->select();
        $this->assign('slider_list', $slider_list);

        // 获取模板订单信息
        $theme_model = D('Site/Theme');
        $con         = array();
        $con['id']   = $info['theme'];
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('请先在后台设置网站模板');
        }

        // 模板自定义配置
        $order_info = D('Order')->where(array('site_id' => 1, 'theme_id' => $info['theme']))->find();
        $theme_info['config'] = json_decode($order_info['config'], true);
        $this->assign('config_info', $theme_info['config']);
        $this->assign('meta_title', "首页");

        // 移动端适配
        $theme_info_name_template = $theme_info['name_path'];
        if (request()->isMobile()) {
            if (C('CURRENT_THEME')) {
                $dir =  './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template;
            } else {
                $dir =  './Application/Site/View/Site/theme/' . $theme_info_name_template;
            }
            if (is_dir($dir . '/wap')) {
                $theme_info_name_template = $theme_info_name_template . "/wap";
            }
        }

        // 显示页面
        if (C('CURRENT_THEME')) {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template;
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/footer.htm');
            $this->assign('lists_side', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/side.htm');
            $this->assign('info', $info);
            $this->display('Site/theme/' . $theme_info_name_template . '/index');
        } else {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info_name_template;
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/footer.htm');
            $this->assign('lists_side', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/side.htm');
            $this->assign('info', $info);
            $this->display('Site/theme/' . $theme_info_name_template . '/index');
        }
    }

    /**
     * 文章列表
     * @author jry <598821125@qq.com>
     */
    public function lists($cid = '', $tag = '')
    {
        // 网站配置
        $info = $this->info;

        // 获取分类信息
        if ($cid) {
            $con            = array();
            $con['status']  = '1';
            $con['id']      = $cid;
            $category_model = D('Category');
            $category_info  = $category_model->where($con)->find();
            if (!$category_info) {
                $this->error('分类不存在');
            }
            // 获取父分类信息
            if ($category_info['pid']) {
                $con            = array();
                $con['status']  = '1';
                $con['id']      = $category_info['pid'];
                $parent_category_info  = $category_model->where($con)->find();
                $this->assign('parent_category_info', $parent_category_info);
            }

            // 获取与当前分类同级的分类
            if (!$category_info['pid']) {
                $con           = array();
                $con['status'] = '1';
                $con['pid']    = $category_info['pid'];
                $category_list = $category_model->getCategoryTree($category_info['id']);
            } else {
                $con           = array();
                $con['status'] = '1';
                $con['pid']    = $category_info['pid'];
                $category_list = $category_model->getSameLevelCategoryTree($category_info['id']);
            }

            // 获取面包屑导航数据
            $breadcrumb_list = $category_model->getParentCategory($category_info['id']);

            // 获取文章列表
            $article_model = D('Article');
            $con           = array();
            $con['status'] = '1';
            $con['cid']    = $cid;
            $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
            $data_list     = $article_model->where($con)->page($p, 10)->select();

            // 分页
            $page = new \lyf\Page(
                $article_model->where($con)->count(),
                10
            );
            $this->assign('page', $page->show());
        }

        // 获取模板订单信息
        $theme_model = D('Site/Theme');
        $con         = array();
        $con['id']   = $info['theme'];
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('请先在后台设置网站模板');
        }

        // 模板自定义配置
        $order_info = D('Order')->where(array('site_id' => 1, 'theme_id' => $info['theme']))->find();
        $theme_info['config'] = json_decode($order_info['config'], true);
        $this->assign('config_info', $theme_info['config']);


        if ($tag) {
            $tag_object = D("Site/article");
            $article_list = $tag_object->where(['status'=>1])->order('sort desc,id asc')->select();
            $data_list = array();
            foreach ($article_list as $item){
                $array = explode(",",$item['tags']);
                if (in_array($tag,$array)){
                    $data_list[] = $item;
                }
            }
        }
        $this->assign('tag', $tag);
        $this->assign('category_info', $category_info);
        $this->assign('category_list', $category_list);
        $this->assign('breadcrumb_list', $breadcrumb_list);
        $this->assign('data_list', $data_list);
        $this->assign('meta_title', $category_info['title'] . "-" . $info['title']);

        // 移动端适配
        $theme_info_name_template = $theme_info['name_path'];
        if (request()->isMobile()) {
            if (C('CURRENT_THEME')) {
                $dir =  './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template;
            } else {
                $dir =  './Application/Site/View/Site/theme/' . $theme_info_name_template;
            }
            if (is_dir($dir . '/wap')) {
                $theme_info_name_template = $theme_info_name_template . "/wap";
            }
        }

        // 显示页面
        if (C('CURRENT_THEME')) {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template;
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/footer.htm');
            $this->assign('lists_side', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/side.htm');
            $this->display('Site/theme/' . $theme_info_name_template . '/' . ($category_info['lists_template'] ?: 'lists_list'));
        } else {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info_name_template;
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/footer.htm');
            $this->assign('lists_side', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/side.htm');
            $this->display('Site/theme/' . $theme_info_name_template . '/' . ($category_info['lists_template'] ?: 'lists_list'));
        }
    }

    /**
     * 单页
     * @author jry <598821125@qq.com>
     */
    public function page($cid)
    {
        // 网站配置
        $info = $this->info;

        // 获取分类信息
        $category_model = D('Category');
        $con            = array();
        $con['status']  = '1';
        $con['id']      = $cid;
        $category_info  = $category_model->where($con)->find();
        if (!$category_info) {
            $this->error('分类不存在');
        }

        // 获取父分类信息
        if ($category_info['pid']) {
            $con            = array();
            $con['status']  = '1';
            $con['id']      = $category_info['pid'];
            $parent_category_info  = $category_model->where($con)->find();
            $this->assign('parent_category_info', $parent_category_info);
        }

        // 获取与当前分类同级的分类
        if (!$category_info['pid']) {
            $con           = array();
            $con['status'] = '1';
            $con['pid']    = $category_info['pid'];
            $category_list = $category_model->getCategoryTree($category_info['id']);
        } else {
            $con           = array();
            $con['status'] = '1';
            $con['pid']    = $category_info['pid'];
            $category_list = $category_model->getSameLevelCategoryTree($category_info['id']);
        }

        // 获取面包屑导航数据
        $breadcrumb_list = $category_model->getParentCategory($category_info['id']);

        // 获取模板订单信息
        $theme_model = D('Site/Theme');
        $con         = array();
        $con['id']   = $info['theme'];
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('请先在后台设置网站模板');
        }

        // 模板自定义配置
        $order_info = D('Order')->where(array('site_id' => 1, 'theme_id' => $info['theme']))->find();
        $theme_info['config'] = json_decode($order_info['config'], true);
        $this->assign('config_info', $theme_info['config']);

        $this->assign('category_info', $category_info);
        $this->assign('category_list', $category_list);
        $this->assign('breadcrumb_list', $breadcrumb_list);
        $this->assign('article_info', $category_info);
        $this->assign('meta_title', $category_info['title'] . "-" . $info['title']);

        // 移动端适配
        $theme_info_name_template = $theme_info['name_path'];
        if (request()->isMobile()) {
            if (C('CURRENT_THEME')) {
                $dir =  './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template;
            } else {
                $dir =  './Application/Site/View/Site/theme/' . $theme_info_name_template;
            }
            if (is_dir($dir . '/wap')) {
                $theme_info_name_template = $theme_info_name_template . "/wap";
            }
        }

        // 显示页面
        if (C('CURRENT_THEME')) {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template;
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/footer.htm');
            $this->assign('lists_side', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/side.htm');
            $this->display('Site/theme/' . $theme_info_name_template . '/' . ($category_info['lists_template'] ?: 'lists_page'));
        } else {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info_name_template;
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/footer.htm');
            $this->assign('lists_side', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/side.htm');
            $this->display('Site/theme/' . $theme_info_name_template . '/' . ($category_info['lists_template'] ?: 'lists_page'));
        }
    }

    /**
     * 文章列表
     * @author jry <598821125@qq.com>
     */
    public function detail($id)
    {
        // 网站配置
        $info = $this->info;

        // 获取文章信息
        $article_model = D('Article');
        $con           = array();
        $con['status'] = '1';
        $article_info  = $article_model->where($con)->detail($id);
        if (!$article_info) {
            $this->error('错误：' . $article_model->getError());
        }


        // 增加阅读次数
        $article_model->where($con)->setInc('view_count');

        // 获取分类信息
        $category_model = D('Category');
        $con            = array();
        $con['status']  = '1';
        $con['id']      = $article_info['cid'];
        $category_info  = $category_model->where($con)->find();
        if (!$category_info) {
            $this->error('分类不存在');
        }

        // 获取与当前分类同级的分类
        if (!$category_info['pid']) {
            $con           = array();
            $con['status'] = '1';
            $con['pid']    = $category_info['pid'];
            $category_list = $category_model->getCategoryTree($category_info['id']);
        } else {
            $con           = array();
            $con['status'] = '1';
            $con['pid']    = $category_info['pid'];
            $category_list = $category_model->getSameLevelCategoryTree($category_info['id']);
        }

        // 获取面包屑导航数据
        $breadcrumb_list = $category_model->getParentCategory($category_info['id']);

        // 获取模板订单信息
        $theme_model = D('Site/Theme');
        $con         = array();
        $con['id']   = $info['theme'];
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('请先在后台设置网站模板');
        }

        // 模板自定义配置
        $order_info = D('Order')->where(array('site_id' => 1, 'theme_id' => $info['theme']))->find();
        $theme_info['config'] = json_decode($order_info['config'], true);
        $this->assign('config_info', $theme_info['config']);

        // 获取评论列表
        $comment_p            = I('p') ?: 1;
        $comment_order        = I('comment_order') ?: 'id desc';
        $comment_list         = D("Site/Comment")->getCommentList($id, 10, $comment_p, $comment_order);
        $this->assign('comment_page', $comment_list['page']);
        $this->assign('category_info', $category_info);
        $this->assign('category_list', $category_list);
        $this->assign('article_info', $article_info);
        $this->assign('breadcrumb_list', $breadcrumb_list);
        $this->assign('meta_title', $article_info['title'] . "-" . $info['title']);

        // 移动端适配
        $theme_info_name_template = $theme_info['name_path'];
        if (request()->isMobile()) {
            if (C('CURRENT_THEME')) {
                $dir =  './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template;
            } else {
                $dir =  './Application/Site/View/Site/theme/' . $theme_info_name_template;
            }
            if (is_dir($dir . '/wap')) {
                $theme_info_name_template = $theme_info_name_template . "/wap";
            }
        }

        // 显示页面
        if (C('CURRENT_THEME')) {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template;
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/footer.htm');
            $this->assign('lists_side', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/side.htm');

            // 详情页模板
            if ($article_info['detail_template']) {
                $detail_tmp = $article_info['detail_template'];
            } else if ($category_info['detail_template']) {
                $detail_tmp = $category_info['detail_template'];
            } else {
                $detail_tmp = 'detail';
            }
            $this->display('Site/theme/' . $theme_info_name_template . '/' . $detail_tmp);
        } else {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info_name_template;
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/footer.htm');
            $this->assign('lists_side', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/side.htm');

            // 详情页模板
            if ($article_info['detail_template']) {
                $detail_tmp = $article_info['detail_template'];
            } else if ($category_info['detail_template']) {
                $detail_tmp = $category_info['detail_template'];
            } else {
                $detail_tmp = 'detail';
            }
            $this->display('Site/theme/' . $theme_info_name_template . '/' . $detail_tmp);
        }
    }

    /**
     * 搜索列表
     * @author jry <598821125@qq.com>
     */
    public function search($keyword)
    {
        // 网站配置
        $info = $this->info;

        // 获取文章列表
        $article_model = D('Article');
        $con           = array();
        $con['status'] = '1';
        $con['cid']    = $cid;
        $con['title']  = array('like', $keyword);
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list     = $article_model->where($con)->page($p, 9)->select();

        // 分页
        $page = new \lyf\Page(
            $article_model->where($con)->count(),
            9
        );

        // 获取模板订单信息
        $theme_model = D('Site/Theme');
        $con         = array();
        $con['id']   = $info['theme'];
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('请先在后台设置网站模板');
        }

        // 模板自定义配置
        $order_info = D('Order')->where(array('site_id' => 1, 'theme_id' => $info['theme']))->find();
        $theme_info['config'] = json_decode($order_info['config'], true);
        $this->assign('config_info', $theme_info['config']);

        $this->assign('data_list', $data_list);
        $this->assign('page', $page->show());
        $this->assign('meta_title', $category_info['title'] . "-" . $info['title']);

        // 移动端适配
        $theme_info_name_template = $theme_info['name_path'];
        if (request()->isMobile()) {
            if (C('CURRENT_THEME')) {
                $dir =  './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template;
            } else {
                $dir =  './Application/Site/View/Site/theme/' . $theme_info_name_template;
            }
            if (is_dir($dir . '/wap')) {
                $theme_info_name_template = $theme_info_name_template . "/wap";
            }
        }

        // 显示页面
        if (C('CURRENT_THEME')) {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template;
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/footer.htm');
            $this->assign('lists_side', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/side.htm');
            $this->display('Site/theme/' . $theme_info_name_template . '/search');
        } else {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info_name_template;
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/footer.htm');
            $this->assign('lists_side', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/side.htm');
            $this->display('Site/theme/' . $theme_info_name_template . '/search');
        }
    }

    /**
     * 留言
     * @author jry <598821125@qq.com>
     */
    public function liuyan()
    {

        // 发表留言
        if (request()->isPost()) {
            $liuyan_model = D('Liuyan');
            $data         = $liuyan_model->create();
            if ($data) {
                $result = $liuyan_model->add($data);
                if ($result) {
                    $this->success('留言发表成功');
                } else {
                    $this->error('留言发表失败：' . $liuyan_model->getError());
                }
            } else {
                $this->error('留言发表失败：' . $liuyan_model->getError());
            }
        }
    }

    /**
     * 表单
     * @author jry <598821125@qq.com>
     */
    public function form($fid)
    {
        $form_model  = D('Site/form');
        $field_model = D('Site/field');

        // 获取模板订单信息
        $theme_model = D('Site/Theme');
        $con         = array();
        $con['id']   = $info['theme'];
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('请先在后台设置网站模板');
        }
        // 提交表单
        if (request()->isPost()) {
            if (!$_POST) {
                $this->error('请填写数据后提交');
            }
            $map2           = array();
            $map2['fid']    = $fid;
            $map2['status'] = 1;
            $field_info     = $field_model->where($map2)->select(); //此表单的字段提出
            foreach ($field_info as $v2) {
                $data[$v2['name']] = $_POST[$v2['name']];
                if (is_array($data[$v2['name']])) {
                    $data[$v2['name']] = implode('/', $data[$v2['name']]);
                }

                //必须全部填完
                if (!$data[$v2['name']]) {
                    $this->error('请填写' . $v2['name']);
                }
            }
            $data        = json_encode($data); //转换成json格式
            $data_model  = D('Site/Data');
            $add         = array();
            $add['data'] = $data;
            $add['fid']  = $fid; //组装写入数据
            $add         = $data_model->create($add);
            if ($add) {
                if ($data_model->add($add) !== false) {
                    $this->success('提交成功');
                } else {
                    $this->error('提交失败');
                }
            } else {
                $this->error($data_model->getError());
            }
        } else {
            // 网站配置
            $info = $this->info;

            // 表单
            $map           = array();
            $map['id']     = $fid;
            $map['status'] = 1;
            $form_info     = $form_model->where($map)->find(); //表单数据提出
            if (!$form_info) {
                $this->error('此表单已停用');
            }
            $map2           = array();
            $map2['fid']    = $fid;
            $map2['status'] = 1;
            $field_info     = $field_model->where($map2)->select(); //此表单的字段提出
            //处理字段数据
            foreach ($field_info as $k1 => &$v1) {
                if ($v1['type'] == 'radio' || $v1['type'] == 'checkbox' || $v1['type'] == 'select') {
                    $v1['choice'] = explode("/", $v1['choose']);
                }
            }
            $this->assign('form_info', $form_info);
            $this->assign('field_info', $field_info);

            // 移动端适配
            $theme_info_name_template = $theme_info['name_path'];
            if (request()->isMobile()) {
                if (C('CURRENT_THEME')) {
                    $dir =  './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template;
                } else {
                    $dir =  './Application/Site/View/Site/theme/' . $theme_info_name_template;
                }
                if (is_dir($dir . '/wap')) {
                    $theme_info_name_template = $theme_info_name_template . "/wap";
                }
            }

            // 显示页面
            if (C('CURRENT_THEME')) {
                // 主题目录
                $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template;
                $this->assign('info', $info);
                $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/header.htm');
                $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/footer.htm');
                $this->assign('lists_side', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info_name_template . '/side.htm');
                $this->display('Site/theme/' . $theme_info_name_template . '/form');
            } else {
                // 主题目录
                $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info_name_template;
                $this->assign('info', $info);
                $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/header.htm');
                $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/footer.htm');
                $this->assign('lists_side', './Application/Site/View/Site/theme/' . $theme_info_name_template . '/side.htm');
                $this->display('Site/theme/' . $theme_info_name_template . '/form');
            }
        }
    }
}

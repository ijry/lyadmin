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

        $info             = C('site_config');
        $info['logo_url'] = get_cover($info['logo']);
        $info['homepage'] = U('Site/Site/index');
        $this->info       = $info;

        // 增加访问次数
        $con       = array();
        $con['id'] = C('Site_config.theme');
        D('Theme')->where($con)->setInc('view_count');
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
        $con['id']   = C('Site_config.theme');
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('请先在后台设置网站模板');
        }

        // 模板自定义配置
        $theme_info['config'] = json_decode($theme_info['config'], true);
        $this->assign('config_info', $theme_info['config']);

        $this->assign('meta_title', "首页");

        // 显示页面
        if (C('CURRENT_THEME')) {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'];
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->assign('info', $info);
            $this->display('Site/theme/' . $theme_info['name'] . '/index');
        } else {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info['name'];
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->assign('info', $info);
            $this->display('Site/theme/' . $theme_info['name'] . '/index');
        }
    }

    /**
     * 文章列表
     * @author jry <598821125@qq.com>
     */
    public function lists($cid)
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
        $con['id']   = C('Site_config.theme');
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('请先在后台设置网站模板');
        }

        // 模板自定义配置
        $theme_info['config'] = json_decode($theme_info['config'], true);
        $this->assign('config_info', $theme_info['config']);

        // 获取文章列表
        $article_model = D('Article');
        $con           = array();
        $con['status'] = '1';
        $con['cid']    = $cid;
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list     = $article_model->where($con)->page($p, 9)->select();

        // 分页
        $page = new \lyf\Page(
            $article_model->where($con)->count(),
            9
        );

        $this->assign('category_info', $category_info);
        $this->assign('category_list', $category_list);
        $this->assign('breadcrumb_list', $breadcrumb_list);
        $this->assign('data_list', $data_list);
        $this->assign('page', $page->show());
        $this->assign('meta_title', $category_info['title'] . "-" . $info['title']);

        // 显示页面
        if (C('CURRENT_THEME')) {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->display('Site/theme/' . $theme_info['name'] . '/' . ($category_info['lists_template'] ?: 'lists_list'));
        } else {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->display('Site/theme/' . $theme_info['name'] . '/' . ($category_info['lists_template'] ?: 'lists_list'));
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
        $con['id']   = C('Site_config.theme');
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('请先在后台设置网站模板');
        }

        // 模板自定义配置
        $theme_info['config'] = json_decode($theme_info['config'], true);
        $this->assign('config_info', $theme_info['config']);

        $this->assign('category_info', $category_info);
        $this->assign('category_list', $category_list);
        $this->assign('breadcrumb_list', $breadcrumb_list);
        $this->assign('article_info', $category_info);
        $this->assign('meta_title', $category_info['title'] . "-" . $info['title']);

        // 显示页面
        if (C('CURRENT_THEME')) {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->display('Site/theme/' . $theme_info['name'] . '/' . ($category_info['lists_template'] ?: 'lists_page'));
        } else {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->display('Site/theme/' . $theme_info['name'] . '/' . ($category_info['lists_template'] ?: 'lists_page'));
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
        $con['id']     = $id;
        $article_info  = $article_model->where($con)->find();

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
        $con['id']   = C('Site_config.theme');
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('请先在后台设置网站模板');
        }

        // 模板自定义配置
        $theme_info['config'] = json_decode($theme_info['config'], true);
        $this->assign('config_info', $theme_info['config']);

        $this->assign('category_info', $category_info);
        $this->assign('category_list', $category_list);
        $this->assign('breadcrumb_list', $breadcrumb_list);
        $this->assign('article_info', $article_info);
        $this->assign('meta_title', $article_info['title'] . "-" . $info['title']);

        // 显示页面
        if (C('CURRENT_THEME')) {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/footer.htm');

            // 详情页模板
            if ($article_info['detail_template']) {
                $detail_tmp = $article_info['detail_template'];
            } else if ($category_info['detail_template']) {
                $detail_tmp = $category_info['detail_template'];
            } else {
                $detail_tmp = 'detail';
            }
            $this->display('Site/theme/' . $theme_info['name'] . '/' . $detail_tmp);
        } else {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/footer.htm');

            // 详情页模板
            if ($article_info['detail_template']) {
                $detail_tmp = $article_info['detail_template'];
            } else if ($category_info['detail_template']) {
                $detail_tmp = $category_info['detail_template'];
            } else {
                $detail_tmp = 'detail';
            }
            $this->display('Site/theme/' . $theme_info['name'] . '/' . $detail_tmp);
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
        $con['id']   = C('Site_config.theme');
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('请先在后台设置网站模板');
        }

        // 模板自定义配置
        $theme_info['config'] = json_decode($theme_info['config'], true);
        $this->assign('config_info', $theme_info['config']);

        $this->assign('data_list', $data_list);
        $this->assign('page', $page->show());
        $this->assign('meta_title', $category_info['title'] . "-" . $info['title']);

        // 显示页面
        if (C('CURRENT_THEME')) {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->display('Site/theme/' . $theme_info['name'] . '/search');
        } else {
            // 主题目录
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->display('Site/theme/' . $theme_info['name'] . '/search');
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
        $con['id']   = C('Site_config.theme');
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

            // 显示页面
            if (C('CURRENT_THEME')) {
                // 主题目录
                $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'];
                $this->assign('info', $info);
                $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/header.htm');
                $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/footer.htm');
                $this->display('Site/theme/' . $theme_info['name'] . '/form');
            } else {
                // 主题目录
                $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info['name'];
                $this->assign('info', $info);
                $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/header.htm');
                $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/footer.htm');
                $this->display('Site/theme/' . $theme_info['name'] . '/form');
            }
        }
    }
}

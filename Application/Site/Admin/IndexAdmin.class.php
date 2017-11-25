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
namespace Site\Admin;

use Admin\Controller\AdminController;

/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexAdmin extends AdminController
{
    /**
     * 首页
     * @author jry <598821125@qq.com>
     */
    public function index()
    {

        // 统计分类总数
        $category_model = D('Site/Category');
        $con            = array();
        $con['status']  = array('egt', 0);
        $category_count = $category_model->where($con)->count();
        $this->assign('category_count', $category_count);
        // 统计文章总数
        $article_model = D('Site/Article');
        $con           = array();
        $con['status'] = array('egt', 0);
        $article_count = $article_model->where($con)->count();
        $this->assign('article_count', $article_count);
        // 统计我的模板
        $theme_model   = D('Site/Theme');
        $con           = array();
        $con['status'] = array('egt', 0);
        $theme_count   = $theme_model->where($con)->count();
        $this->assign('theme_count', $theme_count);
        // 获取当前模板信息
        $theme_model = D('Site/Theme');
        $con         = array();
        $con['id']   = C('Site_config.theme');
        $theme_info  = $theme_model->where($con)->find();
        $this->assign('theme_info', $theme_info);
        $this->assign('meta_title', "网站后台管理");
        $this->display();
    }

    /**
     * 模板自定义配置
     * @author jry <598821125@qq.com>
     */
    public function theme_config()
    {
        // 获取模板信息
        $theme_model = D('Theme');
        $theme_info  = $theme_model->find(C('Site_config.theme'));
        if (!$theme_info) {
            $this->error('模板不存在');
        }
        // 保存配置
        if (request()->isPost()) {
            $config    = I('config');
            $con       = array();
            $con['id'] = C('Site_config.theme');
            $flag      = $theme_model->where($con)->setField('config', json_encode($config));
            if ($flag !== false) {
                $this->success('保存成功', U('Site/Index/index'));
            } else {
                $this->error('保存失败：' . $theme_model->getError());
            }
        } else {

            $db_config = $theme_info['config'];
            if (C('CURRENT_THEME')) {
                $addon['config'] = include './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/config.php';
            } else {
                $addon['config'] = include './Application/Site/View/Site/theme/' . $theme_info['name'] . '/config.php';
            }

            if ($db_config) {
                $db_config = json_decode($db_config, true);
                if (count($addon['config']) >= 1) {
                    foreach ($addon['config'] as $key => $value) {
                        if ($value['type'] != 'group' && isset($db_config[$key])) {
                            $addon['config'][$key]['value'] = $db_config[$key];
                        } else {
                            if (isset($value['options'])) {
                                foreach ($value['options'] as $gourp => $options) {
                                    if (isset($options['options'])) {
                                        foreach ($options['options'] as $gkey => $value) {
                                            $addon['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // 构造表单名
            foreach ($addon['config'] as $key => $val) {
                if ($val['type'] == 'group') {
                    foreach ($val['options'] as $key2 => $val2) {
                        foreach ($val2['options'] as $key3 => $val3) {
                            $addon['config'][$key]['options'][$key2]['options'][$key3]['name'] = 'config[' . $key3 . ']';
                        }
                    }
                } else {
                    $addon['config'][$key]['name'] = 'config[' . $key . ']';
                }
            }
            $this->assign('form_items', $addon['config']);
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('模板自定义配置') //设置页面标题
                ->setPostUrl(U('')) //设置表单提交地址
                ->setExtraItems($addon['config']) //直接设置表单数据
                ->setFormData($addon)
                ->display();
        }
    }

}

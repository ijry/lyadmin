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
    public function index($site_id = 1)
    {
        // 站点信息
        $index_model = D('Site/Index');
        $con         = array();
        $con['id']   = $site_id;
        $info        = $index_model->where($con)->find();

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
        $con['id']   = $info['theme'];
        $theme_info  = $theme_model->where($con)->find();
        $this->assign('theme_info', $theme_info);
        $this->assign('meta_title', "网站后台管理");
        $this->display();
    }

    /**
     * 设置
     * @author jry <598821125@qq.com>
     */
    public function settings($site_id = 1)
    {
        // 权限检测
        $index_model = D('Site/Index');
        $con         = array();
        $con['id']   = $site_id;
        $info        = $index_model->where($con)->find();

        // 保存设置
        if (request()->isPost()) {
            $data = $index_model->create();
            if ($data) {
                $id = $index_model->where(array('id' => $site_id))->save($data);
                if ($id) {
                    $this->success('保存成功', U('', array('site_id' => $site_id)));
                } else {
                    $this->error('保存失败' . $index_model->getError());
                }
            } else {
                $this->error($index_model->getError());
            }
        } else {
            // 获取网站信息
            $con       = array();
            $con['id'] = $site_id;
            $info      = $index_model->where($con)->find();

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('网站设置') // 设置页面标题
                ->setPostUrl(U('', array('site_id' => $site_id))) // 设置表单提交地址
                ->addFormItem('title', 'text', '网站名称', '填写您的网站名称', '', array('must' => 1))
                ->addFormItem('logo', 'picture_temp', '网站logo', '上传您的网站logo，最好200K以内', '', array('self' => array('upload_driver' => C('sites_config.upload_driver') ?: 'Qiniu'), 'must' => 1))
                ->addFormItem('description', 'textarea', '站点描述', '站点描述')
                ->addFormItem('keywords', 'textarea', '站点关键词', '站点关键词')
                ->addFormItem('company', 'text', '公司名称', '填写您的公司名称')
                ->addFormItem('email', 'text', '联系邮箱', '填写您的常用邮箱')
                ->addFormItem('phone', 'text', '联系电话', '填写您的常用电话')
                ->addFormItem('address', 'text', '公司地址', '填写您的公司地址')
                ->addFormItem('icp', 'text', 'ICP备案号', 'ICP备案号')
                ->addFormItem('qq', 'text', 'QQ', 'QQ')
                ->addFormItem('qqqun', 'text', 'QQ群', 'QQ群')
                ->addFormItem('weibo', 'text', '微博地址', '微博地址')
                ->addFormItem('qr_code', 'picture_temp', '二维码', '二维码', '', array('self' => array('upload_driver' => C('sites_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('qr_weixin', 'picture_temp', '微信二维码', '微信二维码', '', array('self' => array('upload_driver' => C('sites_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('qr_ios', 'picture_temp', 'iOS二维码', 'iOS二维码', '', array('self' => array('upload_driver' => C('sites_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('qr_android', 'picture_temp', '安卓二维码', '安卓二维码', '', array('self' => array('upload_driver' => C('sites_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('qr_weixin_app', 'picture_temp', '微信小程序', '微信小程序码', '', array('self' => array('upload_driver' => C('sites_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('site_statics', 'textarea', '站点统计', '站点统计代码')
                ->setFormData($info)
                ->display();
        }
    }

    /**
     * 模板自定义配置
     * @author jry <598821125@qq.com>
     */
    public function theme_config($site_id = 1)
    {
        // 权限检测
        $index_model = D('Site/Index');
        $con         = array();
        $con['id']   = $site_id;
        $info        = $index_model->where($con)->find();

        // 获取模板信息
        $theme_model = D('Theme');
        $theme_info  = $theme_model->find($info['theme']);
        if (!$theme_info) {
            $this->error('模板不存在');
        }
        $order_info = D('Order')->where(array('site_id' => $site_id, 'theme_id' => $theme_info['id']))->find();

        // 保存配置
        if (request()->isPost()) {
            $config    = I('config');
            $con       = array();
            $con['id'] = $order_info['id'];
            $flag      = D('Order')->where($con)->setField('config', json_encode($config));
            if ($flag !== false) {
                $this->success('保存成功', U('Site/Index/index'));
            } else {
                $this->error('保存失败：' . $theme_model->getError());
            }
        } else {

            $db_config = $order_info['config'];
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

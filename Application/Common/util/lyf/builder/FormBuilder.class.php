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
namespace lyf\builder;

use Common\Controller\Controller;

/**
 * 表单页面自动生成器
 * @author jry <598821125@qq.com>
 */
class FormBuilder extends Controller
{
    private $_meta_title; // 页面标题
    private $_tab_nav = array(); // 页面Tab导航
    private $_post_url; // 表单提交地址
    private $_form_items  = array(); // 表单项目
    private $_extra_items = array(); // 额外已经构造好的表单项目
    private $_form_data   = array(); // 表单数据
    private $_extra_html  = array(); // 额外功能代码
    private $_ajax_submit = true; // 是否ajax提交
    private $_submit_title; // 确定按钮文本自定义
    private $_bottom_button_list = array(); // 底部工具按钮组
    private $_hide_bottom_cancel = false; // 是否隐藏取消按钮
    private $_template; // 模版

    /**
     * 架构函数 取得模板对象实例
     * @access public
     */
    public function __construct()
    {
        //实例化视图类
        $this->view = \Think\Think::instance('Think\View');
        //控制器初始化
        if (method_exists($this, '_initialize')) {
            $this->_initialize();
        }
    }

    /**
     * 析构方法
     * @access public
     */
    public function __destruct()
    {}

    /**
     * 初始化方法
     * @return $this
     * @author jry <598821125@qq.com>
     */
    protected function _initialize()
    {
        $this->_template = BUILDER_DIR . 'layout/' . strtolower(MODULE_MARK) . '/form.html';
    }

    /**
     * 设置页面标题
     * @param $title 标题文本
     * @return $this
     * @author jry <598821125@qq.com>
     */
    public function setMetaTitle($meta_title)
    {
        $this->_meta_title = $meta_title;
        return $this;
    }

    /**
     * 设置Tab按钮列表
     * @param $tab_list    Tab列表  array('title' => '标题', 'href' => 'http://www.corethink.cn')
     * @param $current_tab 当前tab
     * @return $this
     * @author jry <598821125@qq.com>
     */
    public function setTabNav($tab_list, $current_tab)
    {
        $this->_tab_nav = array('tab_list' => $tab_list, 'current_tab' => $current_tab);
        return $this;
    }

    /**
     * 直接设置表单项数组
     * @param $form_items 表单项数组
     * @return $this
     * @author jry <598821125@qq.com>
     */
    public function setExtraItems($extra_items)
    {
        $this->_extra_items = $extra_items;
        return $this;
    }

    /**
     * 设置表单提交地址
     * @param $url 提交地址
     * @return $this
     * @author jry <598821125@qq.com>
     */
    public function setPostUrl($post_url)
    {
        $this->_post_url = $post_url;
        return $this;
    }

    /**
     * 加入一个表单项
     * @param $type 表单类型(取值参考系统配置FORM_ITEM_TYPE)
     * @param $title 表单标题
     * @param $tip 表单提示说明
     * @param $name 表单名
     * @param $options 表单options
     * @param $extra 额外自定义项目
     * @param $extra_attr 表单项额外属性
     * @return $this
     * @author jry <598821125@qq.com>
     */
    public function addFormItem($name, $type, $title, $tip, $options = array(), $extra = '', $extra_attr = '')
    {
        $item['name']           = $name;
        $item['type']           = $type;
        $item['title']          = $title;
        $item['tip']            = $tip;
        $item['options']        = $options;
        $item['extra']          = array();
        $item['extra']['class'] = '';
        $item['extra']['self']  = '';
        $item['extra']['must']  = 0;
        if (is_array($extra)) {
            $item['extra']['class'] = $extra['class']; // 存放css类
            $item['extra']['self']  = $extra['self']; // 存放每个表单类型的自定义参数
            $item['extra']['must']  = $extra['must']; // 是否必填
        } else {
            $item['extra']['class'] = $extra;
        }
        $item['extra']['attr'] = $extra_attr;
        $this->_form_items[]   = $item;
        return $this;
    }

    /**
     * 设置表单表单数据
     * @param $form_data 表单数据
     * @return $this
     * @author jry <598821125@qq.com>
     */
    public function setFormData($form_data)
    {
        $this->_form_data = $form_data;
        return $this;
    }

    /**
     * 设置额外功能代码
     * @param $extra_html 额外功能代码
     * @return $this
     * @author jry <598821125@qq.com>
     */
    public function setExtraHtml($extra_html, $position = 'bottom')
    {
        $this->_extra_html[$position] = $extra_html;
        return $this;
    }

    /**
     * 设置提交方式
     * @param $title 标题文本
     * @return $this
     * @author jry <598821125@qq.com>
     */
    public function setAjaxSubmit($ajax_submit = true)
    {
        $this->_ajax_submit = $ajax_submit;
        return $this;
    }

    /**
     * 确定按钮文本自定义
     * @param $submit_title 确定按钮文本
     * @return $this
     * @author jry <598821125@qq.com>
     */
    public function setSubmitTitle($submit_title)
    {
        $this->_submit_title = $submit_title;
        return $this;
    }

    /**
     * 添加底部工具按钮
     * @param $title 按钮标题
     * @param $type 按钮类型有两种button和submit
     * @param $class 按钮class
     * @param $href 按钮链接
     * @return $this
     * @author jry <598821125@qq.com>
     */
    public function addBottomButton($button)
    {
        if (!isset($button['title'])) {
            $button['title'] = '自定义按钮';
        }
        if (!isset($button['type'])) {
            $button['type'] = 'submit';
        }
        if (!isset($button['class'])) {
            $button['class'] = 'btn btn-primary';
        }
        $this->_bottom_button_list[] = $button;
        return $this;
    }

    /**
     * 隐藏底部取消按钮
     * @param $template 模版
     * @return $this
     * @author jry <598821125@qq.com>
     */
    public function hideBottomCancel($value)
    {
        $this->_hide_bottom_cancel = $value;
        return $this;
    }

    /**
     * 设置页面模版
     * @param $template 模版
     * @return $this
     * @author jry <598821125@qq.com>
     */
    public function setTemplate($template)
    {
        $this->_template = $template;
        return $this;
    }

    /**
     * 显示页面
     * @author jry <598821125@qq.com>
     */
    public function display()
    {
        // 额外已经构造好的表单项目与单个组装的的表单项目进行合并
        if (count($this->_extra_items) > 0) {
            if (count($this->_form_items) > 0) {
                $base_tab_item['group']['type']                        = 'group';
                $base_tab_item['group']['options']['_base']['title']   = '基本';
                $base_tab_item['group']['options']['_base']['options'] = $this->_form_items;
                $_extra_items                                          = $this->_extra_items;
                if (isset($_extra_items['group']) && $_extra_items['group']['type'] === 'group' && count($_extra_items) == 1) {
                    $base_tab_item['group']['options'] = array_merge($base_tab_item['group']['options'], $_extra_items['group']['options']);
                    $this->_form_items                 = $base_tab_item;
                } else {
                    $this->_form_items = array_merge($this->_form_items, $this->_extra_items);
                }
            } else {
                $this->_form_items = $this->_extra_items;
            }
        }

        // 编译表单值
        if ($this->_form_data) {
            foreach ($this->_form_items as &$item) {
                if ($item['type'] === 'group') {
                    foreach ($item['options'] as $key1 => &$val1) {
                        foreach ($val1['options'] as $key2 => &$val2) {
                            if ($this->_form_data[$val2['name']] && !isset($val2['value'])) {
                                $val2['value'] = $this->_form_data[$val2['name']];
                            }
                        }
                    }
                } else {
                    if (isset($this->_form_data[$item['name']]) && !isset($item['value'])) {
                        $item['value'] = $this->_form_data[$item['name']];
                    }
                }
            }
        }

        $this->assign('meta_title', $this->_meta_title); //页面标题
        $this->assign('tab_nav', $this->_tab_nav); //页面Tab导航
        $this->assign('post_url', $this->_post_url); //标题提交地址
        $this->assign('form_items', $this->_form_items); //表单项目
        $this->assign('form_data', $this->_form_data); //表单项目默认值
        $this->assign('ajax_submit', $this->_ajax_submit); //额外HTML代码
        $this->assign('submit_title', $this->_submit_title); //确定按钮文本自定义
        $this->assign('bottom_button_list', $this->_bottom_button_list); //底部按钮
        $this->assign('hide_bottom_cancel', $this->_hide_bottom_cancel); //隐藏取消按钮
        $this->assign('extra_html', $this->_extra_html); //是否ajax提交

        // 显示页面
        $template = request()->controller() . '/' . request()->action();
        if (is_file($this->view->parseTemplate($template))) {
            parent::display();
        } else {
            $this->assign('is_builder', 'form'); // Builder标记
            parent::display($this->_template);
        }
    }
}

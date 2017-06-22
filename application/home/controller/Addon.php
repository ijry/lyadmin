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
namespace app\home\controller;

/**
 * 扩展控制器
 * 该类参考了OneThink的部分实现
 * 用于调度各个扩展的URL访问需求
 */
class Addon extends Home
{
    /**
     * 外部执行插件方法
     * @author jry <598821125@qq.com>
     */
    public function execute($_addons = null, $_controller = null, $_action = null)
    {
        if (C('URL_CASE_INSENSITIVE')) {
            $_addons     = ucfirst(parse_name($_addons, 1));
            $_controller = parse_name($_controller, 1);
        }

        $TMPL_PARSE_STRING                  = C('TMPL_PARSE_STRING');
        $TMPL_PARSE_STRING['__ADDONROOT__'] = __ROOT__ . "/Addons/{$_addons}";
        C('TMPL_PARSE_STRING', $TMPL_PARSE_STRING);

        if (!empty($_addons) && !empty($_controller) && !empty($_action)) {
            $Addons = A("Addons://{$_addons}/{$_controller}")->$_action();
        } else {
            $this->error('没有指定插件名称，控制器或操作！');
        }
    }

    /**
     * 模板显示 调用内置的模板引擎显示方法，
     * @access protected
     * @param string $template 指定要调用的模板文件
     * @return void
     */
    protected function display($template = '', $vars = [], $replace = [], $config = [])
    {
        $file = T('Addons://' . parse_name($_GET['_addons'], 1) . '@./' . ucfirst($_GET['_controller']) . '/' . $_GET['_action']);
        if (MODULE_MARK === 'Home') {
            if (C('current_theme')) {
                $template = './theme/' . C('current_theme') . '/home/addons/' . parse_name($_GET['_addons'], 1)
                . '/' . ucfirst($_GET['_controller']) . '/' . $_GET['_action'] . '.html';
                if (is_file($template)) {
                    $file = $template;
                }
                if (request()->isMobile()) {
                    $wap_template = './theme/' . C('current_theme') . '/home/wap/addons/' . parse_name($_GET['_addons'], 1)
                    . '/' . ucfirst($_GET['_controller']) . '/' . $_GET['_action'] . '.html';
                    if (is_file($wap_template)) {
                        $file = $wap_template;
                    }
                }
            } else {
                if (request()->isMobile()) {
                    $wap_template = './addons/' . parse_name($_GET['_addons'], 1) . '/view/wap/'
                    . ucfirst($_GET['_controller']) . '/' . $_GET['_action'] . '.html';
                    if (is_file($wap_template)) {
                        $file = $wap_template;
                    }
                }
            }
        }
        define('IS_ADDON', true);
        parent::display($file); // 重要：要避免陷入$this->display()循环
    }
}

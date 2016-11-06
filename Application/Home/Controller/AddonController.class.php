<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <ijry@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;

/**
 * 扩展控制器
 * 该类参考了OneThink的部分实现
 * 用于调度各个扩展的URL访问需求
 */
class AddonController extends HomeController
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
     * @param string $templateFile 指定要调用的模板文件
     * @return void
     */
    protected function display($template = '', $charset = '', $contentType = '', $content = '', $prefix = '')
    {
        $file = T('Addons://' . parse_name($_GET['_addons'], 1) . '@./' . ucfirst($_GET['_controller']) . '/' . $_GET['_action']);
        define('IS_ADDON', true);
        parent::display($file); // 重要：要避免陷入$this->display()循环
    }
}

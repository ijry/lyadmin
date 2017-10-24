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
namespace Common\Controller;

/**
 * 插件类
 * 该类参考了OneThink的部分实现
 * @author jry <598821125@qq.com>
 */
abstract class Addon
{
    /**
     * 视图实例对象
     * @var view
     * @access protected
     * @author jry <598821125@qq.com>
     */
    protected $view          = null;
    public $info             = array();
    public $addon_path       = '';
    public $config_file      = '';
    public $custom_config    = '';
    public $admin_list       = array();
    public $custom_adminlist = '';
    public $access_url       = array();

    /**
     * 构造方法
     * @author jry <598821125@qq.com>
     */
    public function __construct()
    {
        $this->view                         = \Think\Think::instance('Think\View');
        $this->addon_path                   = C('ADDON_PATH') . $this->getName() . '/';
        $TMPL_PARSE_STRING                  = C('TMPL_PARSE_STRING');
        $TMPL_PARSE_STRING['__ADDONROOT__'] = __ROOT__ . '/Addons/' . $this->getName();
        C('TMPL_PARSE_STRING', $TMPL_PARSE_STRING);
        if (is_file($this->addon_path . 'config.php')) {
            $this->config_file = $this->addon_path . 'config.php';
        }
    }

    /**
     * 模板主题设置
     * @access protected
     * @param string $theme 模版主题
     * @return Action
     * @author jry <598821125@qq.com>
     */
    final protected function theme($theme)
    {
        $this->view->theme($theme);
        return $this;
    }

    /**
     * 显示方法
     * @author jry <598821125@qq.com>
     */
    final protected function display($file = '')
    {
        if ($file == '') {
            $file = request()->controller();
        }
        if (MODULE_MARK === 'Home') {
            if (C('CURRENT_THEME')) {
                $template = './Theme/' . C('CURRENT_THEME') . '/Home/Addons/' . $this->getName() . '/' . $file . '.html';
                if (is_file($template)) {
                    $file = $template;
                }
                if (request()->isMobile()) {
                    $wap_template = './Theme/' . C('CURRENT_THEME') . '/Home/Wap/Addons/' . $this->getName() . '/' . $file . '.html';
                    if (is_file($wap_template)) {
                        $file = $wap_template;
                    }
                }
            } else {
                if (request()->isMobile()) {
                    $wap_template = './Addons/' . $this->getName() . '/Wap/' . $file . '.html';
                    if (is_file($wap_template)) {
                        $file = $wap_template;
                    }
                }
            }
        }
        echo ($this->fetch($file));
    }

    /**
     * 模板变量赋值
     * @access protected
     * @param mixed $name 要显示的模板变量
     * @param mixed $value 变量的值
     * @return Action
     * @author jry <598821125@qq.com>
     */
    final protected function assign($name, $value = '')
    {
        $this->view->assign($name, $value);
        return $this;
    }

    /**
     * 用于显示模板的方法
     * @author jry <598821125@qq.com>
     */
    final protected function fetch($templateFile = '')
    {
        if ('' == $templateFile) {
            $templateFile = request()->controller();
        }
        if (!is_file($templateFile)) {
            $templateFile = $this->addon_path
            . $templateFile
            . C('TMPL_TEMPLATE_SUFFIX');
            if (!is_file($templateFile)) {
                throw new \Exception("模板不存在:$templateFile");
            }
        }
        return $this->view->fetch($templateFile);
    }

    /**
     * 获取名称
     * @author jry <598821125@qq.com>
     */
    final public function getName()
    {
        $class = get_class($this);
        return substr($class, strrpos($class, '\\') + 1, -5);
    }

    /**
     * 检查信息
     * @author jry <598821125@qq.com>
     */
    final public function checkInfo()
    {
        $info_check_keys = array(
            'name', 'title', 'description', 'status', 'author', 'version',
        );
        foreach ($info_check_keys as $value) {
            if (!array_key_exists($value, $this->info)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 获取插件的配置数组
     * @author jry <598821125@qq.com>
     */
    final public function getConfig($name = '')
    {
        static $_config = array();
        if (empty($name)) {
            $name = $this->getName();
        }
        if (isset($_config[$name])) {
            return $_config[$name];
        }
        $config        = array();
        $map['name']   = $name;
        $map['status'] = 1;
        $config        = D('Admin/Addon')->where($map)->getField('config');
        if ($config) {
            $config = json_decode($config, true);
        } else {
            $temp_arr = include $this->config_file;
            foreach ($temp_arr as $key => $value) {
                if ($value['type'] == 'group') {
                    foreach ($value['options'] as $gkey => $gvalue) {
                        foreach ($gvalue['options'] as $ikey => $ivalue) {
                            $config[$ikey] = $ivalue['value'];
                        }
                    }
                } else {
                    $config[$key] = $temp_arr[$key]['value'];
                }
            }
        }
        $_config[$name] = $config;
        return $config;
    }

    /**
     * 必须实现安装
     * @author jry <598821125@qq.com>
     */
    abstract public function install();

    /**
     * 必须卸载插件方法
     * @author jry <598821125@qq.com>
     */
    abstract public function uninstall();
}

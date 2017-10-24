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
namespace Admin\Controller;

use Common\Controller\Controller;

/**
 * 后台公共控制器
 * 为什么要继承AdminController？
 * 因为AdminController的初始化函数中读取了顶部导航栏和左侧的菜单，
 * 如果不继承的话，只能复制AdminController中的代码来读取导航栏和左侧的菜单。
 * 这样做会导致一个问题就是当AdminController被官方修改后不会同步更新，从而导致错误。
 * 所以综合考虑还是继承比较好。
 * @author jry <598821125@qq.com>
 */
class AdminController extends Controller
{
    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize()
    {
        // 登录检测
        if (!is_login()) {
            //还没登录跳转到登录页面
            $this->redirect('Admin/Login/login');
        }

        // 演示模式
        // UID>=2时才有效
        if ($_SERVER[ENV_PRE . 'APP_DEMO'] === 'true' && is_login() >= 2) {
            define('APP_DEMO', true);
        } else {
            define('APP_DEMO', false);
        }
        C('APP_DEMO', APP_DEMO);

        // 获取当前访问地址
        $current_url = request()->module() . '/' . request()->controller() . '/' . request()->action();

        // 权限检测，首页不需要权限
        if ('admin/index/index' !== strtolower($current_url)) {
            if (!D('Admin/Group')->checkMenuAuth()) {
                $this->error('权限不足！', U('Admin/Index/index'));
            }
            $this->assign('_admin_tabs', C('ADMIN_TABS'));
        }

        // 获取所有导航
        $module_object = D('Admin/Module');
        $menu_list     = $module_object->getAllMenu();
        $this->assign('_menu_list', $menu_list); // 后台主菜单

        // 获取左侧导航
        if (!C('ADMIN_TABS')) {
            $parent_menu_list = $module_object->getParentMenu();
            if (isset($parent_menu_list[0]['top'])) {
                $current_menu_list = $menu_list[$parent_menu_list[0]['top']];
            } else {
                $current_menu_list = $menu_list[request()->module()];
            }
            $this->assign('_current_menu_list', $current_menu_list); // 后台左侧菜单
            $this->assign('_parent_menu_list', $parent_menu_list); // 后台父级菜单
        }
    }

    /**
     * 用户登录检测
     * @author jry <598821125@qq.com>
     */
    protected function is_login()
    {
        //用户登录检测
        $uid = is_login();
        if ($uid) {
            return $uid;
        } else {
            if (request()->isAjax()) {
                $this->error('请先登录系统', U('Admin/Login/login', '', true, true), array('login' => 1));
            } else {
                redirect(U('Admin/Login/login', '', true, true));
            }
        }
    }

    /**
     * 模块配置方法
     * @author jry <598821125@qq.com>
     */
    public function module_config()
    {
        if (request()->isPost()) {
            $id     = (int) I('id');
            $config = I('config');
            foreach ($config as $key => &$val) {
                if (is_string($val)) {
                    $val = trim($val); // 去除空格
                }
            }
            $module_model = D('Admin/Module');
            $flag         = $module_model->where("id={$id}")->setField('config', json_encode($config));
            if ($flag !== false) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败' . $module_model->getError());
            }
        } else {
            $name        = request()->module();
            $config_file = realpath(APP_DIR . $name) . '/' . D('Admin/Module')->install_file();
            if (!$config_file) {
                $this->error('配置文件不存在');
            }
            $module_config = include $config_file;

            $module_info = D('Admin/Module')->where(array('name' => $name))->find($id);
            $db_config   = $module_info['config'];

            // 构造配置
            if ($db_config) {
                $db_config = json_decode($db_config, true);
                foreach ($module_config['config'] as $key => $value) {
                    if ($value['type'] != 'group') {
                        $module_config['config'][$key]['value'] = $db_config[$key];
                    } else {
                        foreach ($value['options'] as $gourp => $options) {
                            foreach ($options['options'] as $gkey => $value) {
                                $module_config['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
                            }
                        }
                    }
                }
            }

            // 构造表单名
            foreach ($module_config['config'] as $key => $val) {
                if ($val['type'] == 'group') {
                    foreach ($val['options'] as $key2 => $val2) {
                        foreach ($val2['options'] as $key3 => $val3) {
                            $module_config['config'][$key]['options'][$key2]['options'][$key3]['name'] = 'config[' . $key3 . ']';
                        }
                    }
                } else {
                    $module_config['config'][$key]['name'] = 'config[' . $key . ']';
                }
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('设置') //设置页面标题
                ->setPostUrl(U('')) //设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->setExtraItems($module_config['config']) //直接设置表单数据
                ->setFormData($module_info)
                ->display();
        }
    }
}

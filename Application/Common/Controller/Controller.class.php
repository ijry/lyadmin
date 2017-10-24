<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Common\Controller;

/**
 * 公共控制器
 * @author jry <598821125@qq.com>
 */
class Controller extends \Think\Controller
{
    /**
     * 模板显示 调用内置的模板引擎显示方法
     * @access protected
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @param string $charset 输出编码
     * @param string $contentType 输出类型
     * @param string $content 输出内容
     * @param string $prefix 模板缓存前缀
     * @return void
     */
    protected function display($template = '', $charset = '', $contentType = '', $content = '', $prefix = '')
    {
        if (!is_file($template)) {
            $depr = C('TMPL_FILE_DEPR');
            if ('' == $template) {
                // 如果模板文件名为空 按照默认规则定位
                $template = request()->controller() . $depr . request()->action();
            } elseif (false === strpos($template, $depr)) {
                $template = request()->controller() . $depr . $template;
            }
        } else {
            $file = $template;
        }

        // 获取登陆后用户下拉导航
        $mod_con           = array();
        $mod_con['status'] = 1;
        $mod_con['name']   = 'User';
        $_user_nav_main    = D('Admin/Module')->where($mod_con)->getField('user_nav');
        $_user_nav_main    = json_decode($_user_nav_main, true);
        if (isset($_user_nav_main['center'])) {
            $_user_nav_main = $_user_nav_main['center'];
        }

        // 获取模块main导航和center导航
        $mod_con           = array();
        $mod_con['status'] = 1;
        $mod_con['name']   = request()->module();
        $_module_nav       = D('Admin/Module')->where($mod_con)->getField('user_nav');
        $_module_nav       = json_decode($_module_nav, true);
        if (isset($_module_nav['main'])) {
            $_module_nav_main = $_module_nav['main'];
        }
        if (isset($_module_nav['center'])) {
            $_module_nav_center = $_module_nav['center'];
        }

        // 开启默认模块并且开启默认布局
        if (C('DEFAULT_MODULE_LAYOUT') && C('DEFAULT_PUBLIC_LAYOUT') && is_file(C('DEFAULT_PUBLIC_LAYOUT'))) {
            C('HOME_PUBLIC_LAYOUT', C('DEFAULT_PUBLIC_LAYOUT'));
        }

        // 模板赋值
        if (!request()->isAjax() || (request()->isAjax() && C('IS_API_HTML'))) {
            $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
            $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
            $this->assign('_new_message', session('new_message')); // 获取用户未读消息数量
            $this->assign('_user_auth', session('user_auth')); // 用户登录信息
            $this->assign('_user_nav_main', $_user_nav_main); // 用户导航信息
            $this->assign('_module_nav_main', $_module_nav_main); // 模块导航信息
            $this->assign('_module_nav_center', $_module_nav_center); // 模块导航信息
            $this->assign('_module_nav', $_module_nav); // 模块导航信息
            $this->assign('_user_center_side', C('USER_CENTER_SIDE')); // 用户中心侧边
            $this->assign('_user_center_info', C('USER_CENTER_INFO')); // 用户中心信息
            $this->assign('_user_home_info', C('USER_HOME_INFO')); // 用户主页信息
            $this->assign('_admin_public_layout', C('ADMIN_PUBLIC_LAYOUT')); // 页面公共继承模版
            $this->assign('_home_public_layout', C('HOME_PUBLIC_LAYOUT')); // 页面公共继承模版
            $this->assign('_home_public_modal', C('HOME_PUBLIC_MODAL')); // 页面公共继承模版
            $this->assign('_listbuilder_layout', C('LISTBUILDER_LAYOUT')); // ListBuilder继承模版
            $this->assign('_formbuilder_layout', C('FORMBUILDER_LAYOUT')); // FormBuilder继承模版

            // 提示页面继承模板
            if (MODULE_MARK == 'Admin') {
                $this->assign('_info_layout', C('ADMIN_PUBLIC_LAYOUT'));
            } else {
                $this->assign('_info_layout', C('HOME_PUBLIC_LAYOUT'));
            }
            $this->assign('_page_name', strtolower(request()->module() . '_' . request()->controller() . '_' . request()->action()));
            $_current_module = D('Admin/Module')->getFieldByName(request()->module(), 'title'); // 当前模块标题
            $this->assign('_current_module', $_current_module);
            $this->assign('_nav_list_child', D('Admin/Nav')->getNavTreeChild());
        }

        // 渲染页面
        // 记录当前url
        if (request()->isGet() && !(request()->module() == 'User' && request()->controller() == 'User')) {
            session('forward', request()->url(true));
        }

        // 渲染页面
        $this->view->display($template);
    }

    /**
     * 设置一条或者多条数据的状态
     * @param $strict 严格模式要求处理的纪录的uid等于当前登陆用户UID
     * @author jry <598821125@qq.com>
     */
    public function setStatus($model = '', $strict = null)
    {
        if ('' == $model) {
            $model = request()->controller();
        }
        $ids    = array_unique((array) I('ids', 0));
        $status = I('request.status');
        if (empty($ids)) {
            $this->error('请选择要操作的数据');
        }

        // 获取主键
        $status_model      = D($model);
        $model_primary_key = $status_model->getPk();

        // 获取id
        $ids                     = is_array($ids) ? implode(',', $ids) : $ids;
        $map[$model_primary_key] = array('in', $ids);

        // 严格模式
        if ($strict === null) {
            if (MODULE_MARK === 'Home') {
                $strict = true;
            }
        }
        if ($strict) {
            $map['uid'] = array('eq', $this->is_login());
        }
        switch ($status) {
            case 'forbid': // 禁用条目
                $data = array('status' => 0);
                $this->editRow(
                    $model,
                    $data,
                    $map,
                    array('success' => '禁用成功', 'error' => '禁用失败')
                );
                break;
            case 'resume': // 启用条目
                $data = array('status' => 1);
                $map  = array_merge(array('status' => 0), $map);
                $this->editRow(
                    $model,
                    $data,
                    $map,
                    array('success' => '启用成功', 'error' => '启用失败')
                );
                break;
            case 'recycle': // 移动至回收站
                // 查询当前删除的项目是否有子代
                if (in_array('pid', $status_model->getDbFields())) {
                    $count = $status_model->where(array('pid' => array('in', $ids)))->count();
                    if ($count > 0) {
                        $this->error('无法删除，存在子项目！');
                    }
                }

                // 标记删除
                $data['status'] = -1;
                $this->editRow(
                    $model,
                    $data,
                    $map,
                    array('success' => '成功移至回收站', 'error' => '回收失败')
                );
                break;
            case 'restore': // 从回收站还原
                $data = array('status' => 1);
                $map  = array_merge(array('status' => -1), $map);
                $this->editRow(
                    $model,
                    $data,
                    $map,
                    array('success' => '恢复成功', 'error' => '恢复失败')
                );
                break;
            case 'delete': // 删除记录
                // 查询当前删除的项目是否有子代
                // 查询当前删除的项目是否有子代
                if (in_array('pid', $status_model->getDbFields())) {
                    $count = $status_model->where(array('pid' => array('in', $ids)))->count();
                    if ($count > 0) {
                        $this->error('无法删除，存在子项目！');
                    }
                }

                // 删除记录
                $result = $status_model->where($map)->delete();
                if ($result) {
                    $this->success('删除成功，不可恢复！');
                } else {
                    $this->error('删除失败');
                }
                break;
            default:
                $this->error('参数错误');
                break;
        }
    }

    /**
     * 对数据表中的单行或多行记录执行修改 GET参数id为数字或逗号分隔的数字
     * @param string $model 数据模型
     * @param array  $data  修改的数据
     * @param array  $map   查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息
     *                       array(
     *                           'success' => '',
     *                           'error'   => '',
     *                           'url'     => '',   // url为跳转页面
     *                           'ajax'    => false //是否ajax(数字则为倒数计时)
     *                       )
     * @author jry <598821125@qq.com>
     */
    final protected function editRow($model, $data, $map, $msg)
    {
        $msg = array_merge(
            array(
                'success' => '操作成功！',
                'error'   => '操作失败！',
                'url'     => ' ',
                'ajax'    => request()->isAjax(),
            ),
            (array) $msg
        );
        $model  = D($model);
        $result = $model->where($map)->save($data);
        if ($result != false) {
            $this->success($msg['success'] . $model->getError(), $msg['url'], $msg['ajax']);
        } else {
            $this->error($msg['error'] . $model->getError(), $msg['url'], $msg['ajax']);
        }
    }

    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param int $json_option 传递给json_encode的option参数
     * @return void
     */
    protected function ajaxReturn($data, $type = '', $json_option = 0)
    {
        // 演示模式
        if (is_login() && true === APP_DEMO && $data['status'] == 0) {
            $data['info'] = '演示模式已关闭数据库写入功能';
        }

        // sesssion_id
        $data['session_id'] = session_id();
        if (empty($type)) {
            $type = C('DEFAULT_AJAX_RETURN');
        }

        switch (strtoupper($type)) {
            case 'JSON':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data, $json_option));
            case 'XML':
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler = isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler . '(' . json_encode($data, $json_option) . ');');
            case 'EVAL':
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            default:
                // 用于扩展其他返回格式数据
                Hook::listen('ajax_return', $data);
        }
    }
}

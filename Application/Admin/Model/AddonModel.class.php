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
namespace Admin\Model;

use Common\Model\Model;

/**
 * 插件模型
 * 该类参考了OneThink的部分实现
 * @author jry <598821125@qq.com>
 */
class AddonModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'admin_addon';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('name', 'require', '插件名称不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '1,32', '插件名称长度为1-32个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
        array('name', '', '插件名称已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
        array('description', 'require', '钩子描述必须！', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('sort', '0', self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 插件类型
     * @author jry <598821125@qq.com>
     */
    public function addon_type($id)
    {
        $list[0] = '系统插件';
        return $id ? $list[$id] : $list;
    }

    /**
     * 获取插件列表
     * @param string $addon_dir
     * @author jry <598821125@qq.com>
     */
    public function getAllAddon()
    {
        $addon_dir = C('ADDON_PATH');
        $dirs      = array_map('basename', glob($addon_dir . '*', GLOB_ONLYDIR));
        if ($dirs == false || !file_exists($addon_dir)) {
            $this->error = '插件目录不可读或者不存在';
            return false;
        }
        $addons      = array();
        $map['name'] = array('in', $dirs);
        $list        = $this->where($map)
            ->field(true)
            ->order('sort asc,id desc')
            ->select();
        foreach ($list as $addon) {
            $addons[$addon['name']] = $addon;
        }
        foreach ($dirs as $value) {
            if (!isset($addons[$value])) {
                $class = get_addon_class($value);
                if (!class_exists($class)) {
                    // 实例化插件失败忽略执行
                    \Think\Log::record('插件' . $value . '的入口文件不存在！');
                    continue;
                }
                $obj            = new $class;
                $addons[$value] = $obj->info;
                if ($addons[$value]) {
                    $addons[$value]['status'] = -1; // 未安装
                }
            }
        }
        if (C('ADMIN_TABS')) {
            foreach ($addons as &$val) {
                switch ($val['status']) {
                    case '-1': // 未安装
                        $val['status_icon']                          = '<i class="label label-primary">未安装</i>';
                        $val['right_button']['install']['title']     = '安装';
                        $val['right_button']['install']['attribute'] = 'class="label label-success-outline label-pill" href="' . U('install_before', array('addon_name' => $val['name'])) . '"';
                        break;
                    case '0': // 禁用
                        $val['status_icon'] = '<i class="label label-warning">已禁用</i>';
                        if ($val['adminlist']) {
                            $val['right_button']['adminlist']['title']     = '管理';
                            $val['right_button']['adminlist']['attribute'] = 'class="label label-success label-pill open-tab-in-fiframe" title="' . $val['title'] . '" tab-name="' . $val['name'] . '_admin" href="' . U('adminlist', array('addon_name' => $val['name'])) . '"';
                        }
                        $val['right_button']['config']['title']        = '设置';
                        $val['right_button']['config']['attribute']    = 'class="label label-info-outline label-pill open-tab-in-fiframe" title="' . $val['title'] . '" tab-name="' . $val['name'] . '_config" href="' . U('config', array('id' => $val['id'])) . '"';
                        $val['right_button']['forbid']['title']        = '启用';
                        $val['right_button']['forbid']['attribute']    = 'class="label label-success-outline label-pill ajax-get" href="' . U('setStatus', array('status' => 'resume', 'ids' => $val['id'])) . '"';
                        $val['right_button']['uninstall']['title']     = '卸载';
                        $val['right_button']['uninstall']['attribute'] = 'class="label label-danger-outline label-pill" href="' . U('uninstall_before', array('id' => $val['id'])) . '"';
                        break;
                    case '1': // 正常
                        $val['status_icon'] = '<i class="label label-success">已安装</i>';
                        if ($val['adminlist']) {
                            $val['right_button']['adminlist']['title']     = '管理';
                            $val['right_button']['adminlist']['attribute'] = 'class="label label-success label-pill open-tab-in-fiframe" title="' . $val['title'] . '" tab-name="' . $val['name'] . '_admin" href="' . U('adminlist', array('addon_name' => $val['name'])) . '"';
                        }
                        $val['right_button']['config']['title']        = '设置';
                        $val['right_button']['config']['attribute']    = 'class="label label-info-outline label-pill open-tab-in-fiframe" title="' . $val['title'] . '" tab-name="' . $val['name'] . '_config" href="' . U('config', array('id' => $val['id'])) . '"';
                        $val['right_button']['forbid']['title']        = '禁用';
                        $val['right_button']['forbid']['attribute']    = 'class="label label-warning-outline label-pill ajax-get" href="' . U('setStatus', array('status' => 'forbid', 'ids' => $val['id'])) . '"';
                        $val['right_button']['uninstall']['title']     = '卸载';
                        $val['right_button']['uninstall']['attribute'] = 'class="label label-danger-outline label-pill" href="' . U('uninstall_before', array('id' => $val['id'])) . '"';
                        break;
                }
            }
        } else {
            foreach ($addons as &$val) {
                switch ($val['status']) {
                    case '-1': // 未安装
                        $val['status_icon']                          = '<i class="label label-primary">未安装</i>';
                        $val['right_button']['install']['title']     = '安装';
                        $val['right_button']['install']['attribute'] = 'class="label label-success-outline label-pill" href="' . U('install_before', array('addon_name' => $val['name'])) . '"';
                        break;
                    case '0': // 禁用
                        $val['status_icon'] = '<i class="label label-warning">已禁用</i>';
                        if ($val['adminlist']) {
                            $val['right_button']['adminlist']['title']     = '管理';
                            $val['right_button']['adminlist']['attribute'] = 'class="label label-success label-pill" href="' . U('adminlist', array('addon_name' => $val['name'])) . '"';
                        }
                        $val['right_button']['config']['title']        = '设置';
                        $val['right_button']['config']['attribute']    = 'class="label label-info-outline label-pill" href="' . U('config', array('id' => $val['id'])) . '"';
                        $val['right_button']['forbid']['title']        = '启用';
                        $val['right_button']['forbid']['attribute']    = 'class="label label-success-outline label-pill ajax-get" href="' . U('setStatus', array('status' => 'resume', 'ids' => $val['id'])) . '"';
                        $val['right_button']['uninstall']['title']     = '卸载';
                        $val['right_button']['uninstall']['attribute'] = 'class="label label-danger-outline label-pill" href="' . U('uninstall_before', array('id' => $val['id'])) . '"';
                        break;
                    case '1': // 正常
                        $val['status_icon'] = '<i class="label label-success">已安装</i>';
                        if ($val['adminlist']) {
                            $val['right_button']['adminlist']['title']     = '管理';
                            $val['right_button']['adminlist']['attribute'] = 'class="label label-success label-pill" href="' . U('adminlist', array('addon_name' => $val['name'])) . '"';
                        }
                        $val['right_button']['config']['title']        = '设置';
                        $val['right_button']['config']['attribute']    = 'class="label label-info-outline label-pill" href="' . U('config', array('id' => $val['id'])) . '"';
                        $val['right_button']['forbid']['title']        = '禁用';
                        $val['right_button']['forbid']['attribute']    = 'class="label label-warning-outline label-pill ajax-get" href="' . U('setStatus', array('status' => 'forbid', 'ids' => $val['id'])) . '"';
                        $val['right_button']['uninstall']['title']     = '卸载';
                        $val['right_button']['uninstall']['attribute'] = 'class="label label-danger-outline label-pill" href="' . U('uninstall_before', array('id' => $val['id'])) . '"';
                        break;
                }
            }
        }
        return $addons;
    }

    /**
     * 插件显示内容里生成访问插件的url
     * @param string $url url
     * @param array $param 参数
     * @author jry <598821125@qq.com>
     */
    public function getAddonUrl($url, $param = array())
    {
        $url        = parse_url($url);
        $case       = C('URL_CASE_INSENSITIVE');
        $addons     = $case ? parse_name($url['scheme']) : $url['scheme'];
        $controller = $case ? parse_name($url['host']) : $url['host'];
        $action     = trim($case ? strtolower($url['path']) : $url['path'], '/');
        // 解析URL带的参数
        if (isset($url['query'])) {
            parse_str($url['query'], $query);
            $param = array_merge($query, $param);
        }
        // 基础参数
        $params = array(
            '_addons'     => $addons,
            '_controller' => $controller,
            '_action'     => $action,
        );
        $params = array_merge($params, $param); //添加额外参数
        return U(MODULE_MARK . '/Addon/execute', $params, true, true);
    }
}

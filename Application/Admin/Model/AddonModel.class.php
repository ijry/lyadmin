<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Model;

use Common\Model\ModelModel;

/**
 * 插件模型
 * 该类参考了OneThink的部分实现
 * @author jry <598821125@qq.com>
 */
class AddonModel extends ModelModel
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
                if (!class_exists($class)) { // 实例化插件失败忽略执行
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
        foreach ($addons as &$val) {
            switch ($val['status']) {
                case '-1': // 未安装
                    $val['status']                               = '<i class="fa fa-trash" style="color:red"></i>';
                    $val['right_button']['install']['title']     = '安装';
                    $val['right_button']['install']['attribute'] = 'class="label label-success ajax-get" href="' . U('install', array('addon_name' => $val['name'])) . '"';
                    break;
                case '0': // 禁用
                    $val['status']                                 = '<i class="fa fa-ban" style="color:red"></i>';
                    $val['right_button']['config']['title']        = '设置';
                    $val['right_button']['config']['attribute']    = 'class="label label-info" href="' . U('config', array('id' => $val['id'])) . '"';
                    $val['right_button']['forbid']['title']        = '启用';
                    $val['right_button']['forbid']['attribute']    = 'class="label label-success ajax-get" href="' . U('setStatus', array('status' => 'resume', 'ids' => $val['id'])) . '"';
                    $val['right_button']['uninstall']['title']     = '卸载';
                    $val['right_button']['uninstall']['attribute'] = 'class="label label-danger ajax-get" href="' . U('uninstall', array('id' => $val['id'])) . '"';
                    if ($val['adminlist']) {
                        $val['right_button']['adminlist']['title']     = '数据管理';
                        $val['right_button']['adminlist']['attribute'] = 'class="label label-success" href="' . U('adminlist', array('name' => $val['name'])) . '"';
                    }
                    break;
                case '1': // 正常
                    $val['status']                                 = '<i class="fa fa-check" style="color:green"></i>';
                    $val['right_button']['config']['title']        = '设置';
                    $val['right_button']['config']['attribute']    = 'class="label label-info" href="' . U('config', array('id' => $val['id'])) . '"';
                    $val['right_button']['forbid']['title']        = '禁用';
                    $val['right_button']['forbid']['attribute']    = 'class="label label-warning ajax-get" href="' . U('setStatus', array('status' => 'forbid', 'ids' => $val['id'])) . '"';
                    $val['right_button']['uninstall']['title']     = '卸载';
                    $val['right_button']['uninstall']['attribute'] = 'class="label label-danger ajax-get" href="' . U('uninstall', array('id' => $val['id'])) . '"';
                    if ($val['adminlist']) {
                        $val['right_button']['adminlist']['title']     = '数据管理';
                        $val['right_button']['adminlist']['attribute'] = 'class="label label-success" href="' . U('adminlist', array('name' => $val['name'])) . '"';
                    }
                    break;
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

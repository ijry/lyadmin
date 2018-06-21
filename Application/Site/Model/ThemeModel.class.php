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
namespace Site\Model;

use Common\Model\Model;

/**
 * 主题模型
 * @author jry <598821125@qq.com>
 */
class ThemeModel extends Model
{
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'site_theme';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('name', 'require', '名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_find(&$result, $options)
    {
        $result['name_path'] = str_replace(array('_'),array('/'), $result['name']);
        $result['user']               = D('Admin/User')->getUserInfo($result['uid']);
        if (C('CURRENT_THEME')) {
            $result['cover_url'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Sites/Site/theme/' . $result['name'] . '/cover.png';
        } else {
            $result['cover_url'] = __ROOT__ . '/Application/Sites/View/Site/theme/' . $result['name'] . '/cover.png';
        }
        $result['create_time_format'] = time_format($result['create_time'], 'Y-m-d H:i:s');
    }

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_select(&$result, $options)
    {
        foreach ($result as &$record) {
            $this->_after_find($record, $options);
        }
    }

    /**
     * 安装描述文件名
     * @author jry <598821125@qq.com>
     */
    public function install_file()
    {
        return 'opencmf.php';
    }

    /**
     * 获取主题列表
     * @param string $addon_dir
     * @author jry <598821125@qq.com>
     */
    public function getAll()
    {
        //获取所有模板（文件夹下必须有$install_file定义的安装描述文件）
        $path           = './Application/Site/View/Site/theme/';
        $dirs           = array_map('basename', glob($path . '*', GLOB_ONLYDIR));
        $theme_dir_list = array();
        foreach ($dirs as $dir) {
            $config_file = $path . $dir . '/' . $this->install_file();
            if (is_file($config_file)) {
                $theme_dir_list[]                      = $dir;
                $temp_arr                              = include $config_file;
                $temp_arr['info']['status']            = -1; //未安装
                $temp_arr['info']['name']              = $dir;
                $theme_list[$temp_arr['info']['name']] = $temp_arr['info'];
            }
        }
        // extend目录扩展模板
        $path           = './Application/Site/View/Site/theme/extend/';
        $dirs           = array_map('basename', glob($path . '*', GLOB_ONLYDIR));
        foreach ($dirs as $dir) {
            $config_file = $path . $dir . '/' . $this->install_file();
            if (is_file($config_file)) {
                $theme_dir_list[]                      = 'extend' . '_' . $dir;
                $temp_arr                              = include $config_file;
                $temp_arr['info']['status']            = -1; //未安装
                $temp_arr['info']['name']              = 'extend' . '_' . $dir;
                $theme_list[$temp_arr['info']['name']] = $temp_arr['info'];
            }
        }

        // 获取系统已经安装的主题信息
        if ($theme_dir_list) {
            $map['name'] = array('in', $theme_dir_list);
        } else {
            return false;
        }
        $installed_theme_list = $this->where($map)
            ->field(true)
            ->order('sort asc,id desc')
            ->select();
        if ($installed_theme_list) {
            foreach ($installed_theme_list as $theme) {
                $theme_list[$theme['name']] = $theme;
            }
        }

        // 右侧按钮
        foreach ($theme_list as &$val) {
            switch ($val['status']) {
                case '-1': //未安装
                    $val['status']                               = '<i class="label label-primary">未安装</i>';
                    if (C('DEVELOP_MODE')) {
                        $val['right_button']['install']['title']     = '安装';
                        $val['right_button']['install']['attribute'] = 'class="label label-success-outline label-pill" href="' . U('install_before', array('name' => $val['name'])) . '"';
                    }
                    break;
                default:
                    $val['status_icon'] = '<i class="label label-success">已安装</i>';
                    $val['right_button']['update_info']['title']     = '更新信息';
                    $val['right_button']['update_info']['attribute'] = 'class="label label-info-outline label-pill ajax-get" href="' . U('updateInfo', array('id' => $val['id'])) . '"';
                    if (C('DEVELOP_MODE')) {
                        $val['right_button']['uninstall']['title']       = '卸载';
                        $val['right_button']['uninstall']['attribute']   = 'class="label label-danger-outline label-pill" href="' . U('uninstall_before', array('id' => $val['id'])) . '"';
                    }
                    break;
            }
        }
        return $theme_list;
    }
}

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
 * 插件钩子模型
 * 该类参考了OneThink的部分实现
 * @author jry <598821125@qq.com>
 */
class HookModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'admin_hook';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('name', 'require', '钩子名称必须！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '1,32', '钩子名称长度为1-32个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
        array('name', '', '钩子名称已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
        array('description', 'require', '钩子描述必须！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
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
     * 获取件所需的钩子是否存在，没有则新增
     * @param string $str  钩子名称
     * @param string $addons  插件名称
     * @param string $addons  件简介
     * @author jry <598821125@qq.com>
     */
    public function existHook($name, $data)
    {
        $where['name'] = $name;
        $gethook       = $this->where($where)->find();
        if (!$gethook || empty($gethook) || !is_array($gethook)) {
            $data['name']        = $name;
            $data['description'] = $data['description'];
            $data['type']        = 1;
            if (false !== $this->create($data)) {
                $this->add();
            }
        }
    }

    /**
     * 更新插件里的所有钩子对应的插件
     * @alter jry <598821125@qq.com>
     */
    public function updateHooks($addons_name)
    {
        $addons_class = get_addon_class($addons_name); //获取插件名
        if (!class_exists($addons_class)) {
            $this->error = "未实现{$addons_name}插件的入口文件";
            return false;
        }
        $methods = get_class_methods($addons_class);
        $hooks   = $this->getField('name', true);
        $common  = array_intersect($hooks, $methods);
        if (!empty($common)) {
            foreach ($common as $hook) {
                $flag = $this->updateAddons($hook, array($addons_name));
                if (false === $flag) {
                    $this->removeHooks($addons_name);
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 更新单个钩子处的插件
     * @alter jry <598821125@qq.com>
     */
    public function updateAddons($hook_name, $addons_name)
    {
        $o_addons = $this->where("name='{$hook_name}'")->getField('addons');
        if ($o_addons) {
            $o_addons = explode(',', $o_addons);
        }
        if ($o_addons) {
            $addons = array_merge($o_addons, $addons_name);
            $addons = array_unique($addons);
        } else {
            $addons = $addons_name;
        }
        $flag = $this->where("name='{$hook_name}'")
            ->setField('addons', implode(',', $addons));
        if (false === $flag) {
            $this->where("name='{$hook_name}'")
                ->setField('addons', implode(',', $o_addons));
        }
        return $flag;
    }

    /**
     * 去除插件所有钩子里对应的插件数据
     * @alter jry <598821125@qq.com>
     */
    public function removeHooks($addons_name)
    {
        $addons_class = get_addon_class($addons_name);
        if (!class_exists($addons_class)) {
            return false;
        }
        $methods = get_class_methods($addons_class);
        $hooks   = $this->getField('name', true);
        $common  = array_intersect($hooks, $methods);
        if ($common) {
            foreach ($common as $hook) {
                $flag = $this->removeAddons($hook, array($addons_name));
                if (false === $flag) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 去除单个钩子里对应的插件数据
     * @alter jry <598821125@qq.com>
     */
    public function removeAddons($hook_name, $addons_name)
    {
        $o_addons = $this->where("name='{$hook_name}'")->getField('addons');
        $o_addons = explode(',', $o_addons);
        if ($o_addons) {
            $addons = array_diff($o_addons, $addons_name);
        } else {
            return true;
        }
        $flag = $this->where("name='{$hook_name}'")
            ->setField('addons', implode(',', $addons));
        if (false === $flag) {
            $this->where("name='{$hook_name}'")
                ->setField('addons', implode(',', $o_addons));
        }
        return $flag;
    }
}

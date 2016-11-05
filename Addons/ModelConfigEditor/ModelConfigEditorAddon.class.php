<?php

namespace Addons\ModelConfigEditor;

use Common\Controller\Addon;

/**
 * 模型配置器插件
 * @author yangweijie
 */

class ModelConfigEditorAddon extends Addon
{

    public $info = array(
        'name'        => 'ModelConfigEditor',
        'title'       => '模型配置器',
        'description' => '用于编辑已有模块的配置',
        'status'      => 1,
        'author'      => 'yangweijie',
        'version'     => '0.3',
    );

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    //实现的app_begin钩子方法
    public function AdminIndex($param)
    {
        $module_object = D('Admin/Module');
        $data_list     = $module_object->getAll();
        $addon         = D('Admin/Addon')->where("name='{$this->getName()}'")->getField('id');
        $this->assign('addon_id', $addon);
        $this->assign('list', $data_list);
        $this->display('widget');
    }

}

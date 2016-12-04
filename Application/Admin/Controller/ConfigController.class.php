<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Think\Page;

/**
 * 系统配置控制器
 * @author jry <598821125@qq.com>
 */
class ConfigController extends AdminController
{
    /**
     * 配置列表
     * @param $tab 配置分组ID
     * @author jry <598821125@qq.com>
     */
    public function index($group = 1)
    {
        // 搜索
        $keyword              = I('keyword', '', 'string');
        $condition            = array('like', '%' . $keyword . '%');
        $map['id|name|title'] = array(
            $condition,
            $condition,
            $condition,
            '_multi' => true,
        );

        // 获取所有配置
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $map['group']  = array('eq', $group);
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $config_object = D('Config');
        $data_list     = $config_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('sort asc,id asc')
            ->select();
        $page = new Page(
            $config_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 设置Tab导航数据列表
        $config_group_list = C('CONFIG_GROUP_LIST'); // 获取配置分组
        foreach ($config_group_list as $key => $val) {
            $tab_list[$key]['title'] = $val;
            $tab_list[$key]['href']  = U('index', array('group' => $key));
        }

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('配置列表') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->addTopButton('delete') // 添加删除按钮
            ->setSearch(
                '请输入ID/配置名称/配置标题',
                U('index', array('group' => $group))
            )
            ->setTabNav($tab_list, $group) // 设置页面Tab导航
            ->addTableColumn('id', 'ID')
            ->addTableColumn('name', '名称')
            ->addTableColumn('title', '标题')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    /**
     * 新增配置
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (IS_POST) {
            $config_object = D('Config');
            $data          = $config_object->create();
            if ($data) {
                if ($config_object->add($data)) {
                    S('DB_CONFIG_DATA', null);
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($config_object->getError());
            }
        } else {
            //获取Builder表单类型转换成一维数组
            $form_item_type = C('FORM_ITEM_TYPE');
            foreach ($form_item_type as $key => $val) {
                $form_item_type[$key] = $val[0];
            }

            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增配置') //设置页面标题
                ->setPostUrl(U('add')) //设置表单提交地址
                ->addFormItem('group', 'select', '配置分组', '配置所属的分组', C('CONFIG_GROUP_LIST'))
                ->addFormItem('type', 'select', '配置类型', '配置类型的分组', $form_item_type)
                ->addFormItem('name', 'text', '配置名称', '配置名称')
                ->addFormItem('title', 'text', '配置标题', '配置标题')
                ->addFormItem('value', 'textarea', '配置值', '配置值')
                ->addFormItem('options', 'textarea', '配置项', '如果是单选、多选、下拉等类型 需要配置该项')
                ->addFormItem('tip', 'textarea', '配置说明', '配置说明')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->display();
        }
    }

    /**
     * 编辑配置
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (IS_POST) {
            $config_object = D('Config');
            $data          = $config_object->create();
            if ($data) {
                if ($config_object->save($data)) {
                    S('DB_CONFIG_DATA', null);
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($config_object->getError());
            }
        } else {
            // 获取Builder表单类型转换成一维数组
            $form_item_type = C('FORM_ITEM_TYPE');
            foreach ($form_item_type as $key => $val) {
                $form_item_type[$key] = $val[0];
            }

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑配置') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('group', 'select', '配置分组', '配置所属的分组', C('CONFIG_GROUP_LIST'))
                ->addFormItem('type', 'select', '配置类型', '配置类型的分组', $form_item_type)
                ->addFormItem('name', 'text', '配置名称', '配置名称')
                ->addFormItem('title', 'text', '配置标题', '配置标题')
                ->addFormItem('value', 'textarea', '配置值', '配置值')
                ->addFormItem('options', 'textarea', '配置项', '如果是单选、多选、下拉等类型 需要配置该项')
                ->addFormItem('tip', 'textarea', '配置说明', '配置说明')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(D('Config')->find($id))
                ->display();
        }
    }

    /**
     * 获取某个分组的配置参数
     * @author jry <598821125@qq.com>
     */
    public function group($group = 1)
    {
        //根据分组获取配置
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $map['group']  = array('eq', $group);
        $data_list     = D('Config')
            ->where($map)
            ->order('sort asc,id asc')
            ->select();

        // 设置Tab导航数据列表
        $config_group_list = C('CONFIG_GROUP_LIST'); // 获取配置分组
        foreach ($config_group_list as $key => $val) {
            $tab_list[$key]['title'] = $val;
            $tab_list[$key]['href']  = U('group', array('group' => $key));
        }

        // 构造表单名、解析options
        foreach ($data_list as &$data) {
            $data['name']    = 'config[' . $data['name'] . ']';
            $data['options'] = \Util\Str::parseAttr($data['options']);
        }

        // 使用FormBuilder快速建立表单页面。
        $builder = new \Common\Builder\FormBuilder();
        $builder->setMetaTitle('系统设置') // 设置页面标题
            ->SetTabNav($tab_list, $group) // 设置Tab按钮列表
            ->setPostUrl(U('groupSave')) // 设置表单提交地址
            ->setExtraItems($data_list) // 直接设置表单数据
            ->display();
    }

    /**
     * 批量保存配置
     * @author jry <598821125@qq.com>
     */
    public function groupSave($config)
    {
        if ($config && is_array($config)) {
            $config_object = D('Config');
            foreach ($config as $name => $value) {
                $map = array('name' => $name);
                // 如果值是数组则转换成字符串，适用于复选框等类型
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $config_object->where($map)->setField('value', $value);
            }
        }
        S('DB_CONFIG_DATA', null);
        $this->success('保存成功！');
    }
}

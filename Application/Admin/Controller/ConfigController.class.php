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

use lyf\Page;

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
        $map                  = array();
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

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
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
        if (request()->isPost()) {
            $config_object = D('Config');
            $data          = $config_object->create();
            if ($data) {
                if ($config_object->add($data)) {
                    S('DB_CONFIG_DATA', null);
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败：' . $config_object->getError());
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

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增配置') //设置页面标题
                ->setPostUrl(U('add')) //设置表单提交地址
                ->addFormItem('group', 'select', '配置分组', '配置所属的分组', C('CONFIG_GROUP_LIST'), array('must' => 1))
                ->addFormItem('type', 'select', '配置类型', '配置类型的分组', $form_item_type, array('must' => 1))
                ->addFormItem('name', 'text', '配置名称', '配置名称', '', array('must' => 1))
                ->addFormItem('title', 'text', '配置标题', '配置标题', '', array('must' => 1))
                ->addFormItem('value', 'textarea', '配置值', '配置值')
                ->addFormItem('options', 'textarea', '配置项', '如果是单选、多选、下拉等类型 需要配置该项')
                ->addFormItem('tip', 'textarea', '配置说明', '配置说明')
                ->addFormItem('is_dev', 'radio', '开发模式显示', '是否开发模式才会显示', array('1' => '是', '0' => '否'))
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
        if (request()->isPost()) {
            $config_object = D('Config');
            $data          = $config_object->create();
            if ($data) {
                if ($config_object->save($data)) {
                    S('DB_CONFIG_DATA', null);
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败：' . $config_object->getError());
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

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑配置') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('group', 'select', '配置分组', '配置所属的分组', C('CONFIG_GROUP_LIST'), array('must' => 1))
                ->addFormItem('type', 'select', '配置类型', '配置类型的分组', $form_item_type, array('must' => 1))
                ->addFormItem('name', 'text', '配置名称', '配置名称', '', array('must' => 1))
                ->addFormItem('title', 'text', '配置标题', '配置标题', '', array('must' => 1))
                ->addFormItem('value', 'text', '配置值', '配置值')
                ->addFormItem('options', 'textarea', '配置项', '如果是单选、多选、下拉等类型 需要配置该项')
                ->addFormItem('tip', 'textarea', '配置说明', '配置说明')
                ->addFormItem('is_dev', 'radio', '开发模式显示', '是否开发模式才会显示', array('1' => '是', '0' => '否'))
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
        // 不显示非开发模式下的一些配置
        if (!C('DEVELOP_MODE')) {
            $map['is_dev'] = array('eq', '0');
        }
        $data_list = D('Config')
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
            $data['options'] = \lyf\Str::parseAttr($data['options']);
        }

        // 使用FormBuilder快速建立表单页面
        $builder = new \lyf\builder\FormBuilder();
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
        // 演示模式
        if (APP_DEMO === true) {
            $this->error('演示模式不允许该操作！');
        }

        // 保存数据
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

    /**
     * 切换开关类型状态
     * @author jry <598821125@qq.com>
     */
    public function toggle($name)
    {
        $con          = array();
        $con['type']  = 'toggle';
        $con['name']  = $name;
        $config_model = D('Config');
        $info         = $config_model->where($con)->find();
        if (!$info) {
            $this->error('不存在该配置');
        }
        if ($info['value'] == '1') {
            $result = $config_model->where($con)->setField('value', '0');
            if ($result) {
                $file   = new \lyf\File();
                $result = $file->del_dir(RUNTIME_PATH);
                $this->success('关闭' . $info['title'] . '成功');
            } else {
                $this->error('关闭' . $info['title'] . '失败' . $config_model->getError());
            }
        }
        if ($info['value'] == '0') {
            $result = $config_model->where($con)->setField('value', '1');
            if ($result) {
                $file   = new \lyf\File();
                $result = $file->del_dir(RUNTIME_PATH);
                $this->success('开启' . $info['title'] . '成功');
            } else {
                $this->error('开启' . $info['title'] . '失败' . $config_model->getError());
            }
        }
    }

    /**
     * 设置一条或者多条数据的状态
     * @author jry <598821125@qq.com>
     */
    public function setStatus($model = '', $strict = null)
    {
        if ('' == $model) {
            $model = request()->controller();
        }
        $ids    = I('request.ids');
        $status = I('request.status');
        $info   = D($model)->where(array('id' => $ids))->find();
        if ($info['is_system'] === '1' && $status === 'delete') {
            $this->error('系统配置不允许删除');
        }
        parent::setStatus($model);
    }
}

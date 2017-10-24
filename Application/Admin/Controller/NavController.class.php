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

use \lyf\Tree;

/**
 * 导航控制器
 * @author jry <598821125@qq.com>
 */
class NavController extends AdminController
{
    /**
     * 导航列表
     * @author jry <598821125@qq.com>
     */
    public function index($group = 'main')
    {
        //搜索
        $keyword         = I('keyword', '', 'string');
        $condition       = array('like', '%' . $keyword . '%');
        $map['id|title'] = array(
            $condition,
            $condition,
            '_multi' => true,
        );

        // 获取所有导航
        $map['status'] = array('egt', '0');
        $map['group']  = $group;
        $data_list     = D('Admin/Nav')
            ->where($map)
            ->order('sort asc, id asc')
            ->select();

        // 给文章列表类型加上链接
        foreach ($data_list as &$val) {
            if ($val['type'] == 'post') {
                $val['title'] = '<a href="' . U('Admin/Post/index', array('cid' => $val['id'])) . '">' . $val['title'] . '</a>';
            }
        }

        // 转换成树状列表
        $tree      = new Tree();
        $data_list = $tree->array2tree($data_list);

        // 设置Tab导航数据列表
        $nav_group_list = C('NAV_GROUP_LIST'); // 获取分类分组
        foreach ($nav_group_list as $key => $val) {
            $tab_list[$key]['title'] = $val;
            $tab_list[$key]['href']  = U('index', array('group' => $key));
        }

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('导航列表') // 设置页面标题
            ->addTopButton('addnew', array('href' => U('add', array('group' => $group)))) // 添加新增按钮
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->addTopButton('delete') // 添加删除按钮
            ->setSearch('请输入ID/导航名称', U('index', array('group' => $group)))
            ->setTabNav($tab_list, $group) // 设置页面Tab导航
            ->addTableColumn('id', 'ID')
            ->addTableColumn('icon', '图标', 'icon')
            ->addTableColumn('cover', '封面', 'picture')
            ->addTableColumn('title_show', '标题')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->addRightButton('edit', array('href' => U('edit', array('group' => $group, 'id' => '__data_id__')))) // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    // 根据导航类型设置表单项目
    private $extra_html = <<<EOF
    <script type="text/javascript">
        $(function(){
            $('input[name="type"]').change(function() {
                var type = $(this).val();
                // 链接类型
                if (type == 'link') {
                    $('.item_url').removeClass('hidden');
                    $('.item_content').addClass('hidden');
                    $('.item_module_name').addClass('hidden');
                    $('.item_lists_template').addClass('hidden');
                    $('.item_detail_template').addClass('hidden');
                // 模块类型
                } else if (type == 'module') {
                    $('.item_url').addClass('hidden');
                    $('.item_content').addClass('hidden');
                    $('.item_module_name').removeClass('hidden');
                    $('.item_lists_template').addClass('hidden');
                    $('.item_detail_template').addClass('hidden');
                // 单页类型
                } else if (type == 'page') {
                    $('.item_url').addClass('hidden');
                    $('.item_content').removeClass('hidden');
                    $('.item_module_name').addClass('hidden');
                    $('.item_lists_template').addClass('hidden');
                    $('.item_detail_template').removeClass('hidden');
                // 文章列表类型
                } else if (type == 'post') {
                    $('.item_url').addClass('hidden');
                    $('.item_content').addClass('hidden');
                    $('.item_module_name').addClass('hidden');
                    $('.item_lists_template').removeClass('hidden');
                    $('.item_detail_template').removeClass('hidden');
                } else {
                    $('.item_url').addClass('hidden');
                    $('.item_content').addClass('hidden');
                    $('.item_module_name').addClass('hidden');
                    $('.item_lists_template').addClass('hidden');
                    $('.item_detail_template').addClass('hidden');
                }
            });
        });
    </script>
EOF;

    /**
     * 新增导航
     * @author jry <598821125@qq.com>
     */
    public function add($group)
    {
        if (request()->isPost()) {
            $nav_object = D('Admin/Nav');
            $data       = $nav_object->create();
            if ($data) {
                $id = $nav_object->add($data);
                if ($id) {
                    $this->success('新增成功', U('index', array('group' => $group)));
                } else {
                    $this->error('新增失败：' . $nav_object->getError());
                }
            } else {
                $this->error($nav_object->getError());
            }
        } else {
            // 获取前台模版供选择
            if (C('CURRENT_THEME')) {
                $template_list = \lyf\File::get_dirs(getcwd() . '/Theme/' . C('CURRENT_THEME') . '/Home//Nav');
            } else {
                $template_list = \lyf\File::get_dirs(getcwd() . '/Application/Home/View/Nav');
            }
            foreach ($template_list['file'] as $val) {
                $val = substr($val, 0, -5);
                if (strstr($val, 'lists')) {
                    $template_list_index[$val] = $val;
                } elseif (strstr($val, 'detail')) {
                    $template_list_detail[$val] = $val;
                }
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增导航') // 设置页面标题
                ->setPostUrl(U('', array('group' => $group))) // 设置表单提交地址
                ->addFormItem('group', 'hidden', '导航分组', '导航分组', '', array('must' => 1))
                ->addFormItem('pid', 'select', '上级导航', '上级导航', select_list_as_tree('Admin/Nav', array('group' => $group), '顶级导航'), array('must' => 1))
                ->addFormItem('title', 'text', '导航标题', '导航前台显示标题', '', array('must' => 1))
                ->addFormItem('type', 'radio', '导航类型', '导航类型', D('Admin/Nav')->nav_type(), array('must' => 1))
                ->addFormItem('url', 'text', '外链URL地址', '支持http://格式或者TP的U函数解析格式', '', array('must' => 1))
                ->addFormItem('module_name', 'select', '模块', '选择的模块需要具有前台页面', select_list_as_tree('Admin/Module', null, null, 'name'), 'hidden')
                ->addFormItem('content', 'summernote', '单页内容', '单页内容', null, 'hidden')
                ->addFormItem('target', 'radio', '打开方式', '打开方式', array('' => '当前窗口', '_blank' => '新窗口打开'))
                ->addFormItem('lists_template', 'select', '列表模版', '文档列表模版', $template_list_index, 'hidden')
                ->addFormItem('detail_template', 'select', '详情页模版', '单页使用的模版详情页模版', $template_list_detail, 'hidden')
                ->addFormItem('cover', 'picture', '封面图片', '封面图片')
                ->addFormItem('icon', 'icon', '图标', '导航图标')
                ->addFormItem('api_route', 'text', 'API路由', 'API路由')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(array('type' => 'link', 'group' => $group))
                ->setExtraHtml($this->extra_html)
                ->display();
        }
    }

    /**
     * 编辑导航
     * @author jry <598821125@qq.com>
     */
    public function edit($id, $group)
    {
        if (request()->isPost()) {
            $nav_object = D('Admin/Nav');
            $data       = $nav_object->create();
            if ($data) {
                if ($nav_object->save($data)) {
                    $this->success('更新成功', U('index', array('group' => $group)));
                } else {
                    $this->error('更新失败：' . $nav_object->getError());
                }
            } else {
                $this->error($nav_object->getError());
            }
        } else {
            // 获取导航信息
            $info = D('Admin/Nav')->find($id);

            // 获取前台模版供选择
            if (C('CURRENT_THEME')) {
                $template_list = \lyf\File::get_dirs(getcwd() . '/Theme/' . C('CURRENT_THEME') . '/Home//Nav');
            } else {
                $template_list = \lyf\File::get_dirs(getcwd() . '/Application/Home/View/Nav');
            }
            foreach ($template_list['file'] as $val) {
                $val = substr($val, 0, -5);
                if (strstr($val, 'lists')) {
                    $template_list_index[$val] = $val;
                } elseif (strstr($val, 'detail')) {
                    $template_list_detail[$val] = $val;
                }
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑导航') // 设置页面标题
                ->setPostUrl(U('')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('group', 'hidden', '导航分组', '导航分组', '', array('must' => 1))
                ->addFormItem('pid', 'select', '上级导航', '上级导航', select_list_as_tree('Admin/Nav', array('group' => $group), '顶级导航'), array('must' => 1))
                ->addFormItem('title', 'text', '导航标题', '导航前台显示标题', '', array('must' => 1))
                ->addFormItem('type', 'radio', '导航类型', '导航类型', D('Admin/Nav')->nav_type(), array('must' => 1))
                ->addFormItem('url', 'text', '外链URL地址', '支持http://格式或者TP的U函数解析格式', null, $info['type'] === 'link' ? '' : 'hidden')
                ->addFormItem('module_name', 'select', '模块', '选择的模块需要具有前台页面', select_list_as_tree('Admin/Module', null, null, 'name'), $info['type'] === 'module' ? '' : 'hidden')
                ->addFormItem('content', 'summernote', '单页内容', '单页内容', null, $info['type'] === 'page' ? '' : 'hidden')
                ->addFormItem('target', 'radio', '打开方式', '打开方式', array('' => '当前窗口', '_blank' => '新窗口打开'))
                ->addFormItem('lists_template', 'select', '列表模版', '文档列表模版', $template_list_index, $info['type'] === 'post' ? '' : 'hidden')
                ->addFormItem('detail_template', 'select', '详情页模版', '单页使用的模版详情页模版', $template_list_detail, ($info['type'] === 'post' || $info['type'] === 'page') ? '' : 'hidden')
                ->addFormItem('cover', 'picture', '封面图片', '封面图片')
                ->addFormItem('icon', 'icon', '图标', '导航图标')
                ->addFormItem('api_route', 'text', 'API路由', 'API路由')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData($info)
                ->setExtraHtml($this->extra_html)
                ->display();
        }
    }
}

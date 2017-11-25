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
namespace Site\Admin;

use Admin\Controller\AdminController;

/**
 * 分类控制器
 * @author jry <598821125@qq.com>
 */
class CategoryAdmin extends AdminController
{

    /**
     * 分类列表
     * @author jry <598821125@qq.com>
     */
    public function category()
    {
        $map           = array();
        $map['status'] = array('egt', '0');
        $data_list     = D('Site/Category')->where($map)->order('sort asc,id asc')->select();
        foreach ($data_list as $key => &$val) {
            if ($val['cate_type'] == '0') {
                $val['url']   = U('Site/Article/article', array('cid' => $val['id']));
                $val['title'] = '<a href="' . $val['url'] . '">' . $val['title'] . '</a>';
            }
        }
        // 转换成树状列表
        $tree      = new \lyf\Tree();
        $data_list = $tree->array2tree($data_list);
        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('分类管理') // 设置页面标题
            ->addTopButton('addnew', array('href' => U('category_add', array()))) // 添加新增按钮
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title_show', '分类')
            ->addTableColumn('cover', '封面', 'picture')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->addRightButton('edit', array('href' => U('category_edit', array('id' => '__data_id__')))) // 添加编辑按钮
            ->addRightButton('forbid', array('model' => 'Category')) // 添加禁用/启用按钮
            ->addRightButton('delete', array('model' => 'Category')) // 添加删除按钮
            ->display();
    }
    // 文档类型切换触发操作JS
    private $extra_html = <<<EOF
        <script type="text/javascript">
            //选择模型时页面元素改变
            $(function() {
                $('input[name="cate_type"]').change(function() {
                    var model_id = $(this).val();
                    if (model_id == 2) { //超链接
                        $('.item_url').removeClass('hidden');
                        $('.item_content').addClass('hidden');
                    } else if (model_id == 1) { //单页文档
                        $('.item_url').addClass('hidden');
                        $('.item_content').removeClass('hidden');
                    } else {
                        $('.item_url').addClass('hidden');
                        $('.item_content').addClass('hidden');
                    }
                });
            });
        </script>
EOF;

    /**
     * 新增分类
     * @author jry <598821125@qq.com>
     */
    public function category_add()
    {
        // 新增
        $category_object = D('Site/Category');
        if (request()->isPost()) {
            $data = $category_object->create();
            if ($data) {
                $id = $category_object->add();
                if ($id) {
                    $this->success('新增成功', U('category'));
                } else {
                    $this->error('新增失败' . $category_object->getError());
                }
            } else {
                $this->error($category_object->getError());
            }
        } else {
            // 获取前台模版供选择
            // 获取模板信息
            $theme_model     = D('Site/Theme');
            $con             = array();
            $con['id'] = C('Site_config.theme');
            $theme_info      = $theme_model->where($con)->find();
            if (!$theme_info) {
                $this->error('请先在后台设置网站模板');
            }
            if (C('CURRENT_THEME')) {
                $template_list = \lyf\File::get_dirs(getcwd() . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name']);
            } else {
                $template_list = \lyf\File::get_dirs(getcwd() . '/Application/Site/View/Site/theme/' . $theme_info['name']);
            }
            foreach ($template_list['file'] as $val) {
                $val = substr($val, 0, -4);
                if (strstr($val, 'lists')) {
                    $template_lists[$val] = $val;
                } elseif (strstr($val, 'detail')) {
                    $template_detail[$val] = $val;
                }
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增分类') // 设置页面标题
                ->setPostUrl(U('category_add')) // 设置表单提交地址
                ->addFormItem('pid', 'select', '上级分类', '所属的上级分类', select_list_as_tree('Site/Category', array(), '顶级分类'))
                ->addFormItem('title', 'text', '分类标题', '分类标题')
                ->addFormItem('cate_type', 'radio', '分类内容模型', '分类内容模型', array('0' => '文章', '1' => '单页', '2' => '链接'))
                ->addFormItem('url', 'text', '链接', 'U函数解析的URL或者外链', null, 'hidden')
                ->addFormItem('content', 'kindeditor', '内容', '单页模型填写内容', null, array('class' => 'hidden', 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('cover', 'picture_temp', '分类封面', '分类封面', '', array('self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('banner', 'picture_temp', 'Banner图片', 'Banner图片', null, array('self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('lists_template', 'select', '列表模版', '文章列表或单页模板', $template_lists)
                ->addFormItem('detail_template', 'select', '详情模版', '文章详情页模版', $template_detail)
                ->setExtraHtml($this->extra_html)
                ->display();
        }
    }

    /**
     * 编辑分类
     * @author jry <598821125@qq.com>
     */
    public function category_edit($id)
    {
        // 权限检测
        $category_object = D('Site/Category');
        $category_info   = $category_object->find($id);
        if (!$category_info) {
            $this->error('分类不存在');
        }
        // 编辑
        if (request()->isPost()) {
            $data = $category_object->create();
            if ($data) {
                if ($category_object->save() !== false) {
                    $this->success('更新成功', U('category'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($category_object->getError());
            }
        } else {
            // 获取前台模版供选择
            // 获取模板信息
            $theme_model     = D('Site/Theme');
            $con             = array();
            $con['id'] = C('Site_config.theme');
            $theme_info      = $theme_model->where($con)->find();
            if (!$theme_info) {
                $this->error('请先在后台设置网站模板');
            }
            if (C('CURRENT_THEME')) {
                $template_list = \lyf\File::get_dirs(getcwd() . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name']);
            } else {
                $template_list = \lyf\File::get_dirs(getcwd() . '/Application/Site/View/Site/theme/' . $theme_info['name']);
            }
            foreach ($template_list['file'] as $val) {
                $val = substr($val, 0, -4);
                if (strstr($val, 'lists')) {
                    $template_lists[$val] = $val;
                } elseif (strstr($val, 'detail')) {
                    $template_detail[$val] = $val;
                }
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑分类') // 设置页面标题
                ->setPostUrl(U('category_edit', array('id' => $id))) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('pid', 'select', '上级分类', '所属的上级分类', select_list_as_tree('Site/Category', array(), '顶级分类'))
                ->addFormItem('title', 'text', '分类标题', '分类标题')
                ->addFormItem('cate_type', 'radio', '分类内容模型', '分类内容模型', array('0' => '文章', '1' => '单页', '2' => '链接'))
                ->addFormItem('url', 'text', '链接', 'U函数解析的URL或者外链', null, 'hidden')
                ->addFormItem('content', 'kindeditor', '内容', '单页模型填写内容', null, array('class' => 'hidden', 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('cover', 'picture_temp', '分类封面', '分类封面', null, array('self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('banner', 'picture_temp', 'Banner图片', 'Banner图片', null, array('self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('lists_template', 'select', '列表模版', '文章列表或单页模板', $template_lists)
                ->addFormItem('detail_template', 'select', '详情模版', '文章详情页模版', $template_detail)
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setExtraHtml($this->extra_html)
                ->setFormData($category_info)
                ->display();
        }
    }
}

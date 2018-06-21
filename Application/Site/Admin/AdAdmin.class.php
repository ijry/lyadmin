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
 * 广告控制器
 * @author jry <598821125@qq.com>
 */
class AdAdmin extends AdminController
{
    /**
     * 广告列表
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        //搜索
        $keyword                = I('keyword', '', 'string');
        $condition              = array('like', '%' . $keyword . '%');
        $map['name|name|title'] = array(
            $condition,
            $condition,
            $condition,
            '_multi' => true,
        );

        // 获取所有广告
        $map['status'] = array('egt', '0');
        $data_list     = D('Ad')
            ->where($map)
            ->order('id asc')
            ->select();

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('广告列表') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->addTopButton('delete') // 添加删除按钮
            ->setSearch('请输入ID/名称/标题', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('name', '名称')
            ->addTableColumn('title', '标题')
            ->addTableColumn('type', '类型', 'callback', array(D('Ad'), 'ad_type'))
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    // 根据广告类型设置表单项目
    private $extra_html = <<<EOF
    <script type="text/javascript">
        $(function(){
            $('input[name="type"]').change(function() {
                var type = $(this).val();
                // 文字广告
                if (type == '1') {
                    $('.item_text').removeClass('hidden');
                    $('.item_url').removeClass('hidden');
                    $('.item_picture').addClass('hidden');
                    $('.item_code').addClass('hidden');
                // 图片广告
                } else if (type == '2') {
                    $('.item_text').addClass('hidden');
                    $('.item_url').removeClass('hidden');
                    $('.item_picture').removeClass('hidden');
                    $('.item_code').addClass('hidden');
                // 代码广告
                } else if (type == '3') {
                    $('.item_text').addClass('hidden');
                    $('.item_url').addClass('hidden');
                    $('.item_picture').addClass('hidden');
                    $('.item_code').removeClass('hidden');
                } else {
                    $('.item_text').removeClass('hidden');
                    $('.item_url').removeClass('hidden');
                    $('.item_picture').addClass('hidden');
                    $('.item_code').addClass('hidden');
                }
            });
        });
    </script>
EOF;

    /**
     * 新增广告
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (request()->isPost()) {
            $ad_model = D('Ad');
            $data     = $ad_model->create();
            if ($data) {
                $id = $ad_model->add($data);
                if ($id) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败：' . $ad_model->getError());
                }
            } else {
                $this->error($ad_model->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增广告') // 设置页面标题
                ->setPostUrl(U('')) // 设置表单提交地址
                ->addFormItem('name', 'text', '广告名称', '调用会使用')
                ->addFormItem('title', 'text', '广告标题', '广告前台显示标题')
                ->addFormItem('type', 'radio', '广告类型', '广告类型', D('Ad')->ad_type())
                ->addFormItem('text', 'textarea', '文字广告', '文字广告')
                ->addFormItem('picture', 'picture', '广告图片', '广告图片', null, 'hidden')
                ->addFormItem('code', 'textarea', '广告代码', '广告代码', null, 'hidden')
                ->addFormItem('url', 'text', '外链URL地址', '以http开头的完整地址')
                ->setFormData(array('type' => '1'))
                ->setExtraHtml($this->extra_html)
                ->display();
        }
    }

    /**
     * 编辑广告
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $ad_model = D('Ad');
            $data     = $ad_model->create();
            if ($data) {
                if ($ad_model->save($data)) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败：' . $ad_model->getError());
                }
            } else {
                $this->error($ad_model->getError());
            }
        } else {
            $info = D('Ad')->find($id);

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑广告') // 设置页面标题
                ->setPostUrl(U('')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('name', 'text', '广告名称', '调用会使用')
                ->addFormItem('title', 'text', '广告标题', '广告前台显示标题')
                ->addFormItem('type', 'radio', '广告类型', '广告类型', D('Ad')->ad_type())
                ->addFormItem('text', 'textarea', '文字广告', '文字广告', null, $info['type'] !== '1' ? 'hidden' : '')
                ->addFormItem('picture', 'picture', '广告图片', '广告图片', null, $info['type'] !== '2' ? 'hidden' : '')
                ->addFormItem('code', 'textarea', '广告代码', '广告代码', null, $info['type'] !== '3' ? 'hidden' : '')
                ->addFormItem('url', 'text', '外链URL地址', '以http开头的完整地址', null, $info['type'] === '3' ? '' : 'hidden')
                ->setFormData($info)
                ->setExtraHtml($this->extra_html)
                ->display();
        }
    }
}

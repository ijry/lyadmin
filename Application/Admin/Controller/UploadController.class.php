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
 * 后台上传控制器
 * @author jry <598821125@qq.com>
 */
class UploadController extends AdminController
{
    /**
     * 上传列表
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        //搜索
        $keyword        = I('keyword', '', 'string');
        $condition      = array('like', '%' . $keyword . '%');
        $map['id|path'] = array($condition, $condition, '_multi' => true);

        //获取所有上传
        $map['status'] = array('egt', '0'); //禁用和正常状态
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $upload_object = D('Upload');
        $data_list     = $upload_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('sort desc,id desc')
            ->select();
        $page = new Page(
            $upload_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        foreach ($data_list as &$data) {
            $data['name'] = cut_str($data['name'], 0, 30)
                . '<input class="form-control input-sm" value="'
                . $data['path'] . '">';
        }

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('上传列表') // 设置页面标题
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->addTopButton('delete') // 添加删除按钮
            ->setSearch('请输入ID/上传关键字', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('show', '文件')
            ->addTableColumn('name', '文件名及路径')
            ->addTableColumn('size', '大小', 'byte')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
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
        if (empty($ids)) {
            $this->error('请选择要操作的数据');
        }
        switch ($status) {
            case 'delete': // 删除条目
                if (!is_array($ids)) {
                    $id_list[0] = $ids;
                } else {
                    $id_list = $ids;
                }
                foreach ($id_list as $id) {
                    $upload_info = D('Upload')->find($id);
                    if ($upload_info) {
                        $realpath = realpath('.' . $upload_info['path']);
                        if ($realpath) {
                            array_map("unlink", glob($realpath));
                            if (count(glob($realpath))) {
                                $this->error('删除失败！');
                            } else {
                                $resut = D('Upload')->delete($id);
                                $this->success('删除成功！');
                            }
                        } else {
                            $resut = D('Upload')->delete($id);
                            $this->success('删除成功！');
                        }
                    }
                }
                break;
            default:
                parent::setStatus($model);
                break;
        }
    }

    /**
     * 上传
     * @author jry <598821125@qq.com>
     */
    public function upload()
    {
        $return = json_encode(D('Upload')->upload());
        exit($return);
    }

    /**
     * 下载
     * @author jry <598821125@qq.com>
     */
    public function download($token)
    {
        if (empty($token)) {
            $this->error('token参数错误！');
        }

        //解密下载token
        $file_md5 = \lyf\Crypt::decrypt($token, user_md5(is_login()));
        if (!$file_md5) {
            $this->error('下载链接已过期，请刷新页面！');
        }

        $upload_object = D('Upload');
        $file_id       = $upload_object->getFieldByMd5($file_md5, 'id');
        if (!$upload_object->download($file_id)) {
            $this->error($upload_object->getError());
        }
    }

    /**
     * KindEditor编辑器文件管理
     * @author jry <598821125@qq.com>
     */
    public function fileManager()
    {
        exit(D('Upload')->fileManager());
    }
}

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
namespace app\install\controller;

use lyf\Db;
use lyf\Str;
use think\Config;
use think\Controller;
use think\exception\HttpResponseException;
use think\Request;
use think\Response;
use think\Url;
use think\View as ViewTemplate;

/**
 * 安装控制器
 */
class Index extends Controller
{
    // 初始化方法
    protected function _initialize()
    {
        // 加载配置
        \think\Config::load(APP_DIR . 'install/config/config.php');

        $no_verify = array('index', 'step1', 'complete');
        if (in_array(request()->action(), $no_verify)) {
            return true;
        }
        if (is_file('./data/install.lock')) {
            $this->error('已经成功安装了本系统，请不要重复安装!', url('home/Index/index'));
        } else if ($_SERVER[ENV_PRE . 'DEV_MODE'] === 'true') {
            $this->error('系统处于开发模式，无需安装！', url('home/Index/index'));
        }
    }

    // 安装首页
    public function index()
    {
        $this->redirect('index.php/index/step1');
    }

    // 安装第一步，同意安装协议
    public function step1()
    {
        session('step', '1');
        session('error', false);
        $this->assign('meta_title', "step1");
        return $this->fetch();
    }

    // 安装第二步，检测运行所需的环境设置
    public function step2()
    {
        if (request()->isPost()) {
            if (session('error')) {
                $this->error('环境检测没有通过，请调整环境后重试！');
            } else {
                $this->success('恭喜您环境检测通过', U('step3'));
            }
        } else {
            session('step', '2');
            session('error', false);

            //环境检测
            $this->assign('check_env', check_env());

            //目录文件读写检测
            if (IS_WRITE) {
                $this->assign('check_dirfile', check_dirfile());
            }

            //函数及扩展库检测
            $this->assign('check_func_and_ext', check_func_and_ext());

            $this->assign('meta_title', "step2");
            return $this->fetch();
        }
    }

    // 安装第三步，创建数据库
    public function step3($db = null)
    {
        if (request()->isPost()) {
            //检测数据库配置
            if (!is_array($db) || empty($db['DB_TYPE']) || empty($db['DB_HOST'])
                || empty($db['DB_PORT']) || empty($db['DB_NAME'])
                || empty($db['DB_USER']) || empty($db['DB_PREFIX'])) {
                $this->error('请填写完整的数据库配置');
            } else {
                //缓存数据库配置
                session('db_config', $db);

                //创建数据库连接
                $db_name = $db['DB_NAME'];
                unset($db['DB_NAME']); // 防止不存在的数据库导致连接数据库失败
                $db_instance = Db::getInstance($db);

                //检测数据库连接
                $result1 = $db_instance->execute('select version()');
                if (!$result1) {
                    $this->error('数据库连接失败，请检查数据库配置！');
                }

                //用户选择不覆盖情况下检测是否已存在数据库
                if (I('post.cover') === '0') {
                    //检测是否已存在数据库
                    $result2 = $db_instance->execute('SELECT * FROM information_schema.schemata WHERE schema_name="' . $db_name . '"');
                    if ($result2) {
                        $this->error('该数据库已存在，请更换名称！如需覆盖，请选中覆盖按钮！');
                    }

                    //创建数据库
                    $sql = "CREATE DATABASE IF NOT EXISTS `{$db_name}` DEFAULT CHARACTER SET utf8";
                    $db_instance->execute($sql) || $this->error($db_instance->getError());
                }
            }

            //跳转到数据库安装页面
            $this->success('参数正确开始安装', U('step4'));
        } else {
            session('step', '3');
            session('error', false);
            $rand = Str::randString(6, 3); //生成随机数
            $this->assign('meta_title', "step3");
            return $this->fetch();
        }
    }

    // 安装第四步，安装数据表，创建配置文件
    public function step4()
    {
        session('step', '4');
        session('error', false);
        $this->assign('meta_title', "step4");
        echo $this->fetch();

        //连接数据库
        $db_config   = session('db_config');
        $db_instance = Db::getInstance($db_config);

        //创建数据表
        create_tables($db_instance, $db_config['DB_PREFIX']);

        //生成加密字符串
        $add_chars .= '`~!@#$%^&*()_+-=[]{};:"|,.<>/?';
        $auth = Str::randString(64, '', $add_chars); //生成随机数

        //创建配置文件
        $conf = write_config($db_config, $auth);

        //根据加密字符串更新admin密码的加密结果
        $new_admin_password = user_md5('admin', $auth);
        $sql                = <<<SQL
        UPDATE `{$db_config["DB_PREFIX"]}admin_user` SET `password`='{$new_admin_password}' WHERE `id` = 1;
SQL;
        $result = $db_instance->execute($sql);
        if (!$result) {
            $this->error('管理员新密码设置出错！');
        }

        if (session('error')) {
            $this->error('安装出错', 'index');
        } else {
            echo "<script type=\"text/javascript\">window.location.href=\"" . url('complete') . "\"</script>";
            flush();
            ob_flush();
        }
    }

    // 安装完成
    public function complete()
    {
        if (session('step') !== '4') {
            $this->error('请正确安装系统', U('step1'));
        }

        //写入安装锁定文件(只能在最后一步写入锁定文件，因为锁定文件写入后安装模块将无法访问)
        file_put_contents('./data/install.lock', 'lock');

        session('step', null);
        session('error', null);
        $this->assign('meta_title', "完成");
        return $this->fetch();
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param mixed     $msg 提示信息
     * @param string    $url 跳转的URL地址
     * @param mixed     $data 返回的数据
     * @param integer   $wait 跳转等待时间
     * @param array     $header 发送的Header信息
     * @return void
     */
    protected function success($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        $code = 1;
        if (is_numeric($msg)) {
            $code = $msg;
            $msg  = '';
        }
        if (is_null($url) && isset($_SERVER["HTTP_REFERER"])) {
            $url = $_SERVER["HTTP_REFERER"];
        } elseif ('' !== $url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : Url::build($url);
        }
        $result = [
            'code'       => $code,
            'status'     => $code,
            'info'       => $msg,
            'msg'        => $msg,
            'data'       => $data,
            'url'        => $url,
            'wait'       => $wait,
            'waitSecond' => $wait,
        ];

        $type = $this->getResponseType();
        if ('html' == strtolower($type)) {
            $result = ViewTemplate::instance(Config::get('template'), Config::get('view_replace_str'))
                ->fetch(Config::get('dispatch_success_tmpl'), $result);
        }
        $response = Response::create($result, $type)->header($header);
        throw new HttpResponseException($response);
    }

    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param mixed     $msg 提示信息
     * @param string    $url 跳转的URL地址
     * @param mixed     $data 返回的数据
     * @param integer   $wait 跳转等待时间
     * @param array     $header 发送的Header信息
     * @return void
     */
    protected function error($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        $code = 0;
        if (is_numeric($msg)) {
            $code = $msg;
            $msg  = '';
        }
        if (is_null($url)) {
            $url = Request::instance()->isAjax() ? '' : 'javascript:history.back(-1);';
        } elseif ('' !== $url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : Url::build($url);
        }
        $result = [
            'code'       => $code,
            'status'     => $code,
            'info'       => $msg,
            'msg'        => $msg,
            'data'       => $data,
            'url'        => $url,
            'wait'       => $wait,
            'waitSecond' => $wait,
        ];

        $type = $this->getResponseType();
        if ('html' == strtolower($type)) {
            $result = ViewTemplate::instance(Config::get('template'), Config::get('view_replace_str'))
                ->fetch(Config::get('dispatch_error_tmpl'), $result);
        }
        $response = Response::create($result, $type)->header($header);
        throw new HttpResponseException($response);
    }
}

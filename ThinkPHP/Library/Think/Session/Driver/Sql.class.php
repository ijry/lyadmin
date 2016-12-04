<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think\Session\Driver;

use PDO;

/**
 * 数据库方式Session驱动
 *    CREATE TABLE think_session (
 *      session_id varchar(255) NOT NULL,
 *      session_expire int(11) NOT NULL,
 *      session_data blob,
 *      UNIQUE KEY `session_id` (`session_id`)
 *    );.
 */
class Sql
{
    /**
     * Session有效时间.
     */
    protected $lifeTime = '';

    /**
     * session保存的数据库名.
     */
    protected $sessionTable = '';

    /**
     * 数据库句柄.
     */
    protected $hander = array();

    // PDO连接参数
    protected $options = array(PDO::ATTR_CASE => PDO::CASE_LOWER, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL, PDO::ATTR_STRINGIFY_FETCHES => false);

    /**
     * 解析pdo连接的dsn信息.
     *
     * @param unknown $name 数据库名称
     * @param unknown $host  数据库地址
     * @param unknown $port  端口
     * @param unknown $socket    socket
     * @param unknown $charset   字符集
     *
     * @return string
     */
    protected function parseDsn($name, $host = '127.0.0.1', $port = '', $socket = '', $charset = '')
    {
        $dsn = 'mysql:dbname=' . $name . ';host=' . $host;
        if (!empty($port)) {
            $dsn .= ';port=' . $port;
        } elseif (!empty($socket)) {
            $dsn .= ';unix_socket=' . $socket;
        }
        if (!empty($charset)) {
            //为兼容各版本PHP,用两种方式设置编码
            $this->options[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . $charset;
            $dsn .= ';charset=' . $charset;
        }
        return $dsn;
    }

    /**
     * 打开Session.
     *
     * @param string $savePath
     * @param mixed $sessName
     */
    public function open($savePath, $sessName)
    {
        $this->lifeTime     = C('SESSION_EXPIRE') ? C('SESSION_EXPIRE') : ini_get('session.gc_maxlifetime');
        $this->sessionTable = C('SESSION_TABLE') ? C('SESSION_TABLE') : C('DB_PREFIX') . 'session';
        //分布式数据库
        $host = explode(',', C('DB_HOST'));
        $port = explode(',', C('DB_PORT'));
        $name = explode(',', C('DB_NAME'));
        $user = explode(',', C('DB_USER'));
        $pwd  = explode(',', C('DB_PWD'));
        if (1 == C('DB_DEPLOY_TYPE')) {
            //读写分离
            if (C('DB_RW_SEPARATE')) {
                $w = floor(mt_rand(0, C('DB_MASTER_NUM') - 1));
                if (is_numeric(C('DB_SLAVE_NO'))) { //指定服务器读
                    $r = C('DB_SLAVE_NO');
                } else {
                    $r = floor(mt_rand(C('DB_MASTER_NUM'), count($host) - 1));
                }
                //主数据库链接
                $dsn    = $this->parseDsn((isset($name[$w]) ? $name[$w] : $name[0]), $host[$w], (isset($port[$w]) ? $port[$w] : $port[0]));
                $hander = new PDO($dsn, (isset($user[$w]) ? $user[$w] : $user[0]), (isset($pwd[$w]) ? $pwd[$w] : $pwd[0]));
                if (!$hander) {
                    return false;
                }
                $this->hander[0] = $hander;
                //从数据库链接
                $dsn    = $this->parseDsn((isset($name[$r]) ? $name[$r] : $name[0]), $host[$r], (isset($port[$r]) ? $port[$r] : $port[0]));
                $hander = new PDO($dsn, (isset($user[$r]) ? $user[$r] : $user[0]), (isset($pwd[$r]) ? $pwd[$r] : $pwd[0]));
                if (!$hander) {
                    return false;
                }
                $this->hander[1] = $hander;
                return true;
            }
        }
        //从数据库链接
        $r      = floor(mt_rand(0, count($host) - 1));
        $dsn    = $this->parseDsn((isset($name[$r]) ? $name[$r] : $name[0]), $host[$r], (isset($port[$r]) ? $port[$r] : $port[0]));
        $hander = new PDO($dsn, (isset($user[$r]) ? $user[$r] : $user[0]), (isset($pwd[$r]) ? $pwd[$r] : $pwd[0]));
        if (!$hander) {
            return false;
        }
        $this->hander = $hander;
        return true;
    }

    /**
     * 关闭Session.
     */
    public function close()
    {
        if (is_array($this->hander)) {
            $this->gc($this->lifeTime);
            return ($this->hander[0] = null) && ($this->hander[1] = null);
        }
        $this->gc($this->lifeTime);
        return $this->hander = null;
    }

    /**
     * 读取Session.
     *
     * @param string $sessID
     */
    public function read($sessID)
    {
        $hander = is_array($this->hander) ? $this->hander[1] : $this->hander;
        $res    = $hander->prepare('SELECT session_data AS data FROM ' . $this->sessionTable . " WHERE session_id = '$sessID'   AND session_expire >" . time());
        $res->execute();
        if ($result = $res->fetch(PDO::FETCH_ASSOC)) {
            return $result['data'];
        }
        return '';
    }

    /**
     * 写入Session.
     *
     * @param string $sessID
     * @param string $sessData
     */
    public function write($sessID, $sessData)
    {
        $hander   = is_array($this->hander) ? $this->hander[0] : $this->hander;
        $expire   = time() + $this->lifeTime;
        $sessData = addslashes($sessData);
        $res      = $hander->prepare("SELECT COUNT(*) FROM " . $this->sessionTable . " WHERE `session_id` = '$sessID'");
        $res->execute();
        $result = $res->fetch(PDO::FETCH_ASSOC);
        if ($result['COUNT(*)'] === '1') {
            $res = $hander->exec('UPDATE  ' . $this->sessionTable . " SET `session_data` = '$sessData', `session_expire` = '$expire', `update_time` = '" . time() . "' WHERE `session_id` = '$sessID'");
        } else {
            $res = $hander->exec('INSERT INTO  ' . $this->sessionTable . " (  session_id, session_expire, session_data, update_time)  VALUES( '$sessID', '$expire',  '$sessData', '" . time() . "')");
        }
        if ($res) {
            return true;
        }
        return false;
    }

    /**
     * 删除Session.
     *
     * @param string $sessID
     */
    public function destroy($sessID)
    {
        $hander = is_array($this->hander) ? $this->hander[0] : $this->hander;
        $res    = $hander->exec('DELETE FROM ' . $this->sessionTable . " WHERE session_id = '$sessID'");
        if ($res) {
            return true;
        }
        return false;
    }

    /**
     * Session 垃圾回收.
     *
     * @param string $sessMaxLifeTime
     */
    public function gc($sessMaxLifeTime)
    {
        $hander = is_array($this->hander) ? $this->hander[0] : $this->hander;
        return $hander->exec('DELETE FROM ' . $this->sessionTable . ' WHERE session_expire < ' . time());
    }
}

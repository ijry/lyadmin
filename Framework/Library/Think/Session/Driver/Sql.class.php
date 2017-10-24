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

/**
 * 数据库方式Session驱动
 *    CREATE TABLE ly_admin_session (
 *      session_id varchar(255) NOT NULL,
 *      session_expire int(11) NOT NULL,
 *      session_data blob,
 *      uid int(11) unsigned NOT NULL COMMENT '用户ID',
 *      supdate_time int(11) unsigned NOT NULL COMMENT '更新时间',
 *      UNIQUE KEY `session_id` (`session_id`)
 *    );.
 */
class Sql
{
    /**
     * Session有效时间
     */
    protected $lifeTime = '';

    /**
     * session保存的数据库名
     */
    protected $sessionTable = '';

    /**
     * 数据库句柄
     */
    protected $hander = array();

    /**
     * 打开Session
     * @access public
     * @param string $savePath
     * @param mixed $sessName
     */
    public function open($savePath, $sessName)
    {
        $this->lifeTime     = C('SESSION_EXPIRE') ? C('SESSION_EXPIRE') : ini_get('session.gc_maxlifetime');
        $this->sessionTable = C('SESSION_TABLE') ? C('SESSION_TABLE') : C("DB_PREFIX") . "session";
        $this->hander       = M($this->sessionTable, null);
        if (!$this->hander) {
            return false;
        }
        return true;
    }

    /**
     * 关闭Session
     * @access public
     */
    public function close()
    {
        $this->gc($this->lifeTime);
        return $this->hander = null;
    }

    /**
     * 读取Session
     * @access public
     * @param string $sessID
     */
    public function read($sessID)
    {
        $map                   = array();
        $map['session_id']     = $sessID;
        $map['session_expire'] = array('gt', time());
        $session_info          = $this->hander->where($map)->find();
        return $session_info['session_data'];
    }

    /**
     * 写入Session
     * @access public
     * @param string $sessID
     * @param String $sessData
     */
    public function write($sessID, $sessData)
    {
        try {
            $map               = array();
            $map['session_id'] = $sessID;
            $exist             = $this->hander->where($map)->count();
            if ($exist) {
                $data                   = array();
                $data['session_data']   = $sessData;
                $data['update_time']    = time();
                $data['session_expire'] = time() + $this->lifeTime;
                $result                 = $this->hander->where(array('session_id' => $sessID))->save($data);
            } else {
                $data                   = array();
                $data['session_id']     = $sessID;
                $data['session_data']   = $sessData;
                $data['update_time']    = time();
                $data['session_expire'] = time() + $this->lifeTime;
                $result                 = $this->hander->add($data);
            }
            return $result;
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 删除Session
     * @access public
     * @param string $sessID
     */
    public function destroy($sessID)
    {
        $map               = array();
        $map['session_id'] = $sessID;
        return $this->hander->where($map)->delete();
    }

    /**
     * Session 垃圾回收
     * @access public
     * @param string $sessMaxLifeTime
     */
    public function gc($sessMaxLifeTime)
    {
        $map                   = array();
        $map['session_expire'] = array('lt', time());
        $this->hander->where($map)->delete();
        return true;
    }
}

<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\common\model;

define('APP_DEBUG', config('app_debug'));
define('MAGIC_QUOTES_GPC', false);

/**
 * 公共模型(基于TP3的Model)
 * @author jry <598821125@qq.com>
 */
class Model extends \lyf\Model
{
    /**
     * 架构函数
     * 取得DB类的实例对象 字段检查
     * @access public
     * @param string $name 模型名称
     * @param string $tablePrefix 表前缀
     * @param mixed $connection 数据库连接信息
     */
    public function __construct($name = '', $tablePrefix = '', $connection = '')
    {
        if ($tablePrefix === '') {
            $tablePrefix = config('database.prefix');

        }
        if ($connection === '') {
            $connection = array(
                'db_type'    => config('database.type'),
                'db_user'    => config('database.username'),
                'db_pwd'     => config('database.password'),
                'db_host'    => config('database.hostname'),
                'db_port'    => config('database.hostport'),
                'db_name'    => config('database.database'),
                'db_charset' => config('database.charset'),
            );
        }

        // 模型初始化
        $this->_initialize();
        // 获取模型名称
        if (!empty($name)) {
            if (strpos($name, '.')) {
                // 支持 数据库名.模型名的 定义
                list($this->dbName, $this->name) = explode('.', $name);
            } else {
                $this->name = $name;
            }
        } elseif (empty($this->name)) {
            $this->name = $this->getModelName();
        }
        // 设置表前缀
        if (is_null($tablePrefix)) {
            // 前缀为Null表示没有前缀
            $this->tablePrefix = '';
        } elseif ('' != $tablePrefix) {
            $this->tablePrefix = $tablePrefix;
        } elseif (!isset($this->tablePrefix)) {
            $this->tablePrefix = !empty($this->connection) && !is_null(C($this->connection . '.DB_PREFIX')) ? C($this->connection . '.DB_PREFIX') : C('DB_PREFIX');
        }

        // 数据库初始化操作
        // 获取数据库操作对象
        // 当前模型有独立的数据库连接信息
        $this->db(0, empty($this->connection) ? $connection : $this->connection, true);
    }

    /**
     * 得到当前的数据对象名称
     * @access public
     * @return string
     */
    public function getModelName()
    {
        if (empty($this->name)) {
            $name = substr(get_class($this), 0, -strlen('model'));
            if ($pos = strrpos($name, '\\')) {
                //有命名空间
                $this->name = substr($name, $pos + 1);
            } else {
                $this->name = $name;
            }
        }
        return $this->name;
    }
}

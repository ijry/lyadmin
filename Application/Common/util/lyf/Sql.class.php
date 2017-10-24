<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <59821125@qq.com>
// +----------------------------------------------------------------------
namespace lyf;

/**
 * Sql语句处理执行类
 * @author jry <598821125@qq.com>
 */
class Sql
{
    /**
     * 解析数据库语句函数
     * @param string $sql  带默认前缀的sql语句
     * @param string $tablepre  当前系统前缀
     * @return multitype:string 返回最终需要的sql语句
     */
    public function sql_split($sql, $tablepre)
    {
        if ($tablepre != "ly_") {
            $sql = str_replace("EXISTS `ly_", 'EXISTS `' . $tablepre, $sql);
            $sql = str_replace("TABLE `ly_", 'TABLE `' . $tablepre, $sql);
            $sql = str_replace("LOCK TABLES `ly_", 'LOCK TABLES `' . $tablepre, $sql);
            $sql = str_replace("INSERT INTO `ly_", 'INSERT INTO `' . $tablepre, $sql);
        }
        $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);
        if ($r_tablepre != $s_tablepre) {
            $sql = str_replace($s_tablepre, $r_tablepre, $sql);
        }
        $sql          = str_replace("\r", "\n", $sql);
        $ret          = array();
        $num          = 0;
        $queriesarray = explode(";\n", trim($sql));
        unset($sql);
        foreach ($queriesarray as $query) {
            $ret[$num] = '';
            $queries   = explode("\n", trim($query));
            $queries   = array_filter($queries);
            foreach ($queries as $query) {
                $str1 = substr($query, 0, 1);
                if ($str1 != '#' && $str1 != '-') {
                    $ret[$num] .= $query;
                }
            }
            $num++;
        }
        return $ret;
    }

    /**
     * 执行文件中SQL语句函数
     * @param string $file sql语句文件路径
     * @param string $tablepre  自己的前缀
     * @return multitype:string 返回最终需要的sql语句
     */
    public function execute_sql_from_file($file)
    {
        $sql_data = file_get_contents($file);
        if (!$sql_data) {
            return true;
        }
        $sql_format = $this->sql_split($sql_data, C('DB_PREFIX'));
        $counts     = count($sql_format);
        for ($i = 0; $i < $counts; $i++) {
            $sql = trim($sql_format[$i]);
            D()->execute($sql, true);
        }
        return true;
    }
}

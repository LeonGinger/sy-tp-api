<?php
namespace app\common\utils;
use think\db;
/**
 * 封装的db方法
 */

 class WeDbUtils
 {
    /**
     * $instance 实例
     */
    private static $instance = null;

    private function __construct(){}
    /**
     * 单例模式
     */
    static public function getInstance(){
        if(self::$instance==null)
        {
            self::$instance=new WeDbUtils();
        }
        return self::$instance;
	}
    /**
     * 查询一条字段
     * $database 数据库表明
     * $where 查询条件
     */
    public function find($database, $where = '')
    {
        $result = Db::name($database)
            ->where('deleteTime', NULL)
            ->where($where)
            ->find();
        return $result;
    }

    /**
     * 统计字段-and
     * $database 数据库表明
     * $where 查询条件
     * $field 需要查询的字段
     */
    public function total($database, $where = '', $field = '*')
    {
        $result = Db::name($database)
            ->where('deleteTime', 'NULL')
            ->where($where)
            ->count($field);
        return $result;
    }

    /**
     * 同total
     * 此函数不需要过滤deleteTime为 NULL的条件
     * 多用于视图查询(排行榜) 或 特殊情况(GroupBy)
     */

    public function totalView($database, $where = '', $field = '*')
    {
        $result = Db::name($database)
            ->where($where)
            ->count($field);
        return $result;
    }

    /**
     * @Function totalNoBlackList
     * @Describe *total 过滤黑名单
     * @Author:WongZeeWing
     * @DateTime 2020/3/5 17:02:58
     */

    public function totalNoBlackList($database, $where = '', $field = '*')
    {
        $result = Db::name($database)
            ->where('blackListTime', 'null')
            ->where($where)
            ->count($field);
        return $result;
    }

    /**
     * @Function totalBlackList
     * @Describe *total黑名单数量
     * @Author:WongZeeWing
     * @DateTime 2020/3/4 15:28:37
     */

    public function totalBlackList($database, $where = '', $field = '*')
    {
        $result = Db::name($database)
            ->where('blackListTime', 'not null')
            ->where($where)
            ->count($field);
        return $result;
    }

    /**
     * 查询已删除的记录
     *
     * @Author EDZero 
     * @DateTime 2019-12-23 11:13:36
     * @param [type] $database
     * @param string $where
     * @param string $field
     *
     * @return void
     */
    public function totalno($database, $where = '', $field = '*')
    {
        $result = Db::name($database)
            ->where('deleteTime', 'not null')
            ->where($where)
            ->count($field);
        return $result;
    }

    /**
     * 统计字段-or
     * $database 数据库表明
     * $where 查询条件
     * $field 需要查询的字段
     */
    public function totalOr($database, $where = '', $field = '*')
    {
        $result = Db::name($database)
            ->where('deleteTime', 'NULL')
            ->whereOr($where)
            ->count($field);
        return $result;
    }
    /**
     * 统计字段 
     * $database 数据库表明
     * $where 查询条件
     * $field 需要查询的字段
     */
    public function sum($database, $where = '', $field)
    {
        $result = Db::name($database)
            ->where('deleteTime', 'NULL')
            ->where($where)
            ->sum($field);
        return $result;
    }
    /**
     * 查询字段-and
     * $database 数据库表明
     * $where 查询条件
     * $field 需要查询的字段
     */
    public function select($database, $where = '', $field = '', $offset = 0, $length = 500, $order = null)
    {
        $result = Db::name($database)
            ->where('deleteTime', NULL)
            ->where($where)
            ->field($field)
            ->limit($offset, $length)
            ->order($order)
            ->select();
        return $result;
    }

    /**
     * 同select
     * 此函数不需要过滤deleteTime为 NULL的条件
     * 多用于视图查询(排行榜) 或 特殊情况(GroupBy)
     */
    public function selectView($database, $where = '', $field = '', $offset = 0, $length = 500, $order = null)
    {
        $result = Db::name($database)
            ->where($where)
            ->field($field)
            ->limit($offset, $length)
            ->order($order)
            ->select();
        return $result;
    }

    /**
     * @Function selectNoBlackList
     * @Describe *查询 过滤黑名单
     * @Author:WongZeeWing
     * @DateTime 2020/3/5 17:00:04
     */

    public function selectNoBlackList($database, $where = '', $field = '', $offset = 0, $length = 500, $order = null)
    {
        $result = Db::name($database)
            ->where('blackListTime','null')
            ->where($where)
            ->field($field)
            ->limit($offset, $length)
            ->order($order)
            ->select();
        return $result;
    }

    /**
     * @Function selectBlackList
     * @Describe *查询黑名单
     * @Author:WongZeeWing
     * @DateTime 2020/3/4 15:26:02
     */

    public function selectBlackList($database, $where = '', $field = '', $offset = 0, $length = 500, $order = null)
    {
        $result = Db::name($database)
            ->where('blackListTime','not null')
            ->where($where)
            ->field($field)
            ->limit($offset, $length)
            ->order($order)
            ->select();
        return $result;
    }


    /**
     * 查询deleteTime的数据
     *
     * @Author EDZero 
     * @DateTime 2019-12-23 10:43:26
     * @param [type] $database
     * @param string $where
     * @param string $field
     * @param int $offset
     * @param int $length
     * @param [type] $order
     *
     * @return void
     */
    public function selectno($database, $where = '', $field = '', $offset = 0, $length = 500, $order = null)
    {
        $result = Db::name($database)
            ->where('deleteTime', 'not null')
            ->where($where)
            ->field($field)
            ->limit($offset, $length)
            ->order($order)
            ->select();
        return $result;
    }
    /**
     * 查询字段-and
     * $database 数据库表明
     * $where 查询条件
     * $field 需要查询的字段
     */
    public function selectSQL($database, $where = '', $field)
    {
        $result = Db::query('select ' . $field . ' from ' . $database . ' ' . $where);
        return $result;
    }
    /**
     * 查询字段-or
     * $database 数据库表明
     * $where 查询条件
     * $field 需要查询的字段
     */
    public function selectOr($database, $where = '', $field = '', $offset = 0, $length = null, $order = null)
    {
        $result = Db::name($database)
            ->where('deleteTime', 'NULL')
            ->whereOr($where)
            ->field($field)
            ->limit($offset, $length)
            ->order($order)
            ->select();
        return $result;
    }

    public function selectorderRand($database, $where = '', $field = '', $offset = 0, $length = 500, $order = null)
    {
        $result = Db::name($database)
            ->where('deleteTime', NULL)
            ->where($where)
            ->field($field)
            ->limit($offset, $length)
            ->orderRand($order)
            ->select();
        return $result;
    }

    /**
     * selectOrderRaw
     */
    public function selectOrderRaw($database, $where = '', $field = '', $offset = 0, $length = null, $order = null)
    {
        $result = Db::name($database)
            ->where('deleteTime', NULL)
            ->where($where)
            ->field($field)
            ->limit($offset, $length)
            ->orderRaw($order)
            ->select();
        return $result;
    }

    /**
     * 新增一条数据
     * $database 数据库表明
     * $data 插入数据
     */
    public function insert($database, $data)
    {
        $result = Db::name($database)
            ->insert($data);
        // var_dump($result);
        if ($result)
            return $result;
        else {
            //To Do:错误异常捕捉
            return $this->jsonData(ApiErrDesc::ErrMsg_UPDATE[0], ApiErrDesc::ErrMsg_UPDATE[1]);
        }
    }
    /**
     * 新增多条数据
     * $database 数据库表明
     * $data 插入数据
     */
    public function insertAll($database, $data)
    {
        $result = Db::name($database)
            ->insertAll($data);
        // var_dump($result);
        if ($result)
            return true;
        else {
            //To Do:错误异常捕捉
            return $this->jsonData(ApiErrDesc::ErrMsg_INSERT[0], ApiErrDesc::ErrMsg_INSERT[1]);
        }
    }
    /**
     * 更新一条数据
     * $database 数据库表明
     * $where 更新条件
     * $data 插入数据
     */
    public function update($database, $where = '', $data)
    {
        $result = Db::name($database)
            ->where($where)
            ->data($data)
            ->update();
        // var_dump($result);
        if ($result)
            return true;
        else {
            //To Do:错误异常捕捉
            return $this->jsonData(ApiErrDesc::ErrMsg_UPDATE[0], ApiErrDesc::ErrMsg_UPDATE[1]);
        }
    }
    /**
     * 查询相对应的字段是否重复
     * $database 数据库表明
     * $where 查询小玖条件
     *
     * @Author EDZero 
     * @DateTime 2019-12-19 15:15:17
     * @param [type] $database
     * @param [type] $where
     *
     * @return void
     */
    public function repeat($database, $where)
    {
        try {
            $result = Db::name($database)
                ->where('deleteTime', 'NULL')
                ->where($where)
                ->find();
        } catch (\Throwable $th) {
            exception(ApiErrDesc::ERR_PARAMS[1], ApiErrDesc::ERR_PARAMS[0]);
        }
        if (!empty($result)) {
            exception(ApiErrDesc::ErrMsg_REPEAT[1], ApiErrDesc::ErrMsg_REPEAT[0]);
        }
    }

    /**
     * 批量where in
     *
     * @Author EDZero 
     * @DateTime 2019-12-26 10:24:51
     * @param [type] $database
     * @param string $field
     * @param [type] $arr
     * @param [type] $data
     *
     * @return void
     */
    public function deleteSome($database, $field = 'id', $arr, $data)
    {
        try {
            $result = Db::name($database)
                ->where($field, 'in', $arr)
                ->data($data)
                ->update();
        } catch (\Throwable $th) {

            exception(ApiErrDesc::ERR_PARAMS[1], ApiErrDesc::ERR_PARAMS[0]);
        }

        // var_dump($result);
        if ($result)
            return true;
        else {
            //To Do:错误异常捕捉
            exception(ApiErrDesc::ErrMsg_UPDATE[1], ApiErrDesc::ErrMsg_UPDATE[0]);
        }
    }
    /**
     * 批量where in
     *
     * @Author GNLeon 
     * @DateTime 2020年3月24日 11:01:40
     * @param [type] $database
     * @param string $field
     * @param [type] $arr
     * @param [type] $data
     *
     * @return void
     */
    public function updateSome($database, $field = 'id', $arr, $data)
    {
        try {
            $result = Db::name($database)
                ->where($field, 'in', $arr)
                ->data($data)
                ->update();
        } catch (\Throwable $th) {

            exception(ApiErrDesc::ERR_PARAMS[1], ApiErrDesc::ERR_PARAMS[0]);
        }

        if ($result)
            return true;
        else {
            //To Do:错误异常捕捉
            return true;
            // exception(ApiErrDesc::SUCCESS[1], ApiErrDesc::SUCCESS[0]);
        }
    }


    public function querysql($sql){
        try{
            Db::query($sql);
        }catch(\Throwable $th){
            return $th;
        }
    }
    

}
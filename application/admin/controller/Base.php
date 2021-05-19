<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Request;
//使用工具
use app\common\utils\WechatUtils;
use app\common\utils\WeDbUtils;
use redis\Redis;
/**
 * 基础控制器
 */
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,PATCH,OPTIONS');
header('Access-Control-Allow-Headers:Content-Type, X-ELEME-USERID, X-Eleme-RequestID, X-Shard,X-Shard, X-Eleme-RequestID,X-Adminid,X-Token');

class Base extends Controller
{
	/**
	 * 基类
	 * @Author       GNLEON
	 * @Param
	 * @DateTime     2021-03-26T15:09:04+0800
	 * @LastTime     2021-03-26T15:09:04+0800
	 */
    public function initialize()
    {
    	parent::initialize();   
        $this->request =  Request::instance();
        // $this->Wechat_tool = WechatUtils::getInstance();
        $this->WeDb = WeDbUtils::getInstance();

    }
    // public function initialize()
    // {
    //     parent::initialize();
    //     header('Access-Control-Allow-Origin:*');
    //     header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,PATCH,OPTIONS');
    //     header('Access-Control-Allow-Headers:Content-Type, X-ELEME-USERID, X-Eleme-RequestID, X-Shard,X-Shard, X-Eleme-RequestID,X-Adminid,X-Token');
    // }
    /**
     * 批次随机码
     * @remark:日期加4位流水号，流水号每日更新从0开始
     * 批次 日期  序号  商家ID
     * P 20210519 0001  B1
     * @remark:目前规则P日期序号没有商家id
     * 目前问题最大批次 9999 且整个平台的
     */
    public function random_batch(){
        $redis = new Redis;

        $now_date = date("Ymd",time());
        //当天的批次序号++
        $redis->inc("sy_PiciNumber",1);
        $order_number = $redis->get("sy_PiciNumber");
        $newNumber = substr(strval($order_number+10000),1,4);

        $ran_str = "P".$now_date.$newNumber;
        return $ran_str;
    }
    
}

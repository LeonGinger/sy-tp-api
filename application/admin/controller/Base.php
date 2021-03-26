<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Request;
//使用工具
use app\common\utils\WechatUtils;
use app\common\utils\WeDbUtils;
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
        $this->Wechat_tool = WechatUtils::getInstance();
        $this->WeDb = WeDbUtils::getInstance();

    }
    // public function initialize()
    // {
    //     parent::initialize();
    //     header('Access-Control-Allow-Origin:*');
    //     header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,PATCH,OPTIONS');
    //     header('Access-Control-Allow-Headers:Content-Type, X-ELEME-USERID, X-Eleme-RequestID, X-Shard,X-Shard, X-Eleme-RequestID,X-Adminid,X-Token');
    // }
}

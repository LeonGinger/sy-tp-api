<?php

namespace app\wap\controller;

use think\Controller;
use app\common\auth\JwtAuthWap;
use think\facade\Request;

//使用工具
use app\common\utils\WechatUtils;
use app\common\utils\WeDbUtils;
/**
 * 基础控制器
 */

class Base extends Controller
{
    public $Wechat_tool;
    public $WeDb;
    public $jwtAuthApi;
    public $uid = null;
    public $request = null;


    public function __construct()
    {              
      
        $this->request =  Request::instance();
        $this->Wechat_tool = WechatUtils::getInstance();
        $this->jwtAuthApi = JwtAuthWap::getInstance();
        $this->WeDb = WeDbUtils::getInstance();
        $this->uid = $this->jwtAuthApi->getuid();
    }
    // public function initialize()
    // {
    //     parent::initialize();
    //     header('Access-Control-Allow-Origin:*');
    //     header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,PATCH,OPTIONS');
    //     header('Access-Control-Allow-Headers:Content-Type, X-ELEME-USERID, X-Eleme-RequestID, X-Shard,X-Shard, X-Eleme-RequestID,X-Adminid,X-Token');
    // }
}

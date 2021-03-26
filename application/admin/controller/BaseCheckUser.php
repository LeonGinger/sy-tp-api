<?php

namespace app\admin\controller;

use think\facade\Hook;
use app\common\enums\RouteNo;
/**
 * 用户基础控制器
 */

class BaseCheckUser extends Base
{

    public $adminInfo = '';

    public function initialize()
    {
        parent::initialize();
        //登录钩子
        $params = [];
        if(!array_key_exists($this->request->path(),RouteNo::NOT_WEBAPI)){
            $login_info = Hook::exec('app\\admin\\behavior\\CheckAuth', []);
            $this->adminInfo = $login_info;
        }else{
            $this->adminInfo = NULL;
        }

    }

}

<?php

namespace app\wap\controller;

use think\facade\Hook;

/**
 * 用户基础控制器
 */

class BaseCheckUser extends Base
{

    public $adminInfo = '';

    public function initialize()
    {
        parent::initialize();

        // 监听登录的钩子
        $params = [];
        // $login_info = Hook::exec('app\\wap\\behavior\\CheckAuth', []);
        // $this->adminInfo = $login_info;
    }

}

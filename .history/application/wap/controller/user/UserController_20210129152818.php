<?php

namespace app\wap\controller\user;

use app\wap\controller\Base;
use app\common\enums\ErrorCode;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;

/**
 * 用户相关
 */
class UserController extends Base
{

    public function index(){
    	var_dump('HALO');
    	exit();
    }
    public function set_token(){

        $token = $this->jwtAuthApi->setUid(1)->encode()->getToken();
        return $this->success($toetokenkn);

    }

}
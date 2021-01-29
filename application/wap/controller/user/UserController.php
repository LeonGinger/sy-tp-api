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
        $code = $this->request->param('code');

        var_dump($this->Wechat_tool->userinfo($code));
        exit;
        //var_dump($this->Wechat_tool->send_msg('oS9gewJvTvmu_J-jdqNvXORK2Hj4','AOfLkr3Fn2MXX3-99XJEbjbiEvUuff4zEgPFn5afxEM',['url'=>'','row'=>[]]));
        exit;
        $token = $this->jwtAuthApi->setUid(1)->encode()->getToken();
        return ResultVo::success($token);

    }

    /**
     * H5-自动登录-并获取用户信息
     */
    public function Login()
    {
        # code...
        $code = $this->request->param('code');

        $get_uifno = $this->Wechat_tool->userinfo($code);
        
        return  ResultVo::success($get_uifno);
    }

}
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
//         $code = $this->request->param('code');
// 
//         var_dump($this->Wechat_tool->userinfo($code));
//         exit;
        //var_dump($this->Wechat_tool->send_msg('oS9gewJvTvmu_J-jdqNvXORK2Hj4','AOfLkr3Fn2MXX3-99XJEbjbiEvUuff4zEgPFn5afxEM',['url'=>'','row'=>[]]));
        // exit;
        $token = $this->jwtAuthApi->setUid(1)->encode()->getToken();
        return ResultVo::success($token);

    }

    /**
     * H5-登录-并获取用户信息
     */
    public function Login()
    {
        # code...
        $code = $this->request->param('code');

        $get_uifno = $this->Wechat_tool->userinfo($code);
        if(empty($get_uifno['openid'])){
          return  ResultVo::error(400,$get_uifno);
        }
          //找数据库
          //没-插入  有-返回(并替换相关信息)
        return  ResultVo::success($get_uifno);
    }

    /*用户信息修改保存*/
    public function usave()
    {
        $data = $this->request->param();
        $set_data = [
            'username'=> $this->request->param('username'),
            'user_image'=>$this->request->param('user_image'),
            'phone'=>$this->request->param('phone'),
        ];
        $result = $this->insert('User',$set_data);
        return  ResultVo::success($result);
    }
    /*用户上传头像 */
    public function upload_headimg(){
        
    }

}
<?php

namespace app\wap\controller\user;

use app\wap\controller\Base;
use app\common\enums\ErrorCode;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;
use think\facade\Validate;
use think\facade\Config;
use think\route\Resource;

/**
 * 用户相关
 */
class UserController extends Base
{
    private $table = 'User';
    // public function initialize(){

    // }
    public function index(){
    	var_dump('HALO');
    	exit();
    }
    // 获取一个token
    public function set_token(){
        $uid = $this->request->param('uid');
// 
//         var_dump($this->Wechat_tool->userinfo($code));
//         exit;
        //var_dump($this->Wechat_tool->send_msg('oS9gewJvTvmu_J-jdqNvXORK2Hj4','AOfLkr3Fn2MXX3-99XJEbjbiEvUuff4zEgPFn5afxEM',['url'=>'','row'=>[]]));
        // exit;
        $token = $this->jwtAuthApi->setUid($uid)->encode()->getToken();
        return ResultVo::success($token);

    }

    /**
     * H5-登录-并获取用户信息
     *  array(11) { 
     *       ["openid"]=> string(28) "ox8satwQkcC8KNFz4RBWneEdPQj4" 
     *       ["nickname"]=> string(16) "L⃰e⃰o⃰n⃰" 
     *       ["sex"]=> int(1) 
     *       ["language"]=> string(5) "zh_CN" 
     *       ["city"]=> string(6) "中山" 
     *       ["province"]=> string(6) "广东" 
     *       ["country"]=> string(6) "中国" 
     *       ["headimgurl"]=> string(135) "https://thirdwx.qlogo.cn/mmopen/vi_32/t0ltloaZeqrqJ80z27SzS1tvkyWWToID4Etesz7s8niaGwib57pNgAGibSianFqhdHvfiaNziccB82VSHH9aV7319Bibw/132" 
     *       ["privilege"]=> array(0) { } 
     *       ["unionid"]=> string(28) "oJkVc01SPyw-p5d00fdVQJIn6J4M" 如果有公共平台则有unionid
     *       ["subscribe"]=> int(0) }
     *   
     *  } 
     */
    // 用户登陆/注册
    public function Login()
    {
        # code...
        $code = $this->request->param('code');

        $get_uifno = $this->Wechat_tool->userinfo_oa($code);
        if(empty($get_uifno['openid'])){
          return  ResultVo::error(400,$get_uifno);
        }
    
        $uinfo = $this->WeDb->find($this->table,'open_id = "'.$get_uifno['openid'].'"');
        if($uinfo){
          //更新 有-返回(并替换相关信息)
          $up_data = array(
            // 'username'=>'',
            'gender'=>$get_uifno['sex'],
            'subscribe'=>$get_uifno['subscribe']
          );
          $this->WeDb->update($this->table,'id = '.$uinfo['id'],$up_data);

        }else{
          //没-插入  
          $in_data = array(
            'username'=>$get_uifno['nickname'],
            'gender'=>$get_uifno['sex'],
            // 'phone'=>'',
            'user_image'=>$get_uifno['headimgurl'],
            'open_id'=>$get_uifno['openid'],
            'role_id'=>'4',
            // 'real_name_state'=>'',
            // 'business_notice'=>'',
            'subscribe'=>$get_uifno['subscribe'],
            'create_time'=>date('Y-m-d H:i:s',time())
            // 'deleteTime'=>'',
          );
          $in_result = $this->WeDb->insertGetId($this->table,$in_data);
        }
        //-
        $uinfo = $this->WeDb->find($this->table,'open_id = "'.$get_uifno['openid'].'" and delete_time is null');
        $token = $this->jwtAuthApi->setUid($uinfo['id'])->encode()->getToken();
        $redata = array(
            'uinfo'=>$uinfo,
            'token'=>$token
        );
        return  ResultVo::success($redata);
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
        
        $result = $this->WeDb->insert('User',$set_data);


        return  ResultVo::success($result);
    }
    /*用户上传头像 */
    public function upload_headimg(){
        $file = request()->file('imgurl');
                
        $info = $file->validate(['ext'=>'jpg,jpeg,png'])
                     ->move(Config::get('upload_headimg_path'));
                     
        $source = Config::get('upload_headimg_path') . $info->getSaveName();
        // $url = Config::get('domain_http').''.$info->getSaveName();

        $url  =str_replace(Config::get('upload_headimg_path'),Config::get('domain_http').'uploads/headimg/',$source);
        /**
         * 大小压缩
         * code....  
         **/
        /*返回头像地址 */
        $re_data = array(
          'link'=>$url,
          'dir'=>$source,

        );
        return ResultVo::success($re_data);
    }
    // 查询当前用户信息
    public function this_user(){
        $userid = $this->uid;
        $user = $this->WeDb->selectlink($this->table,'role',"{$this->table}.role_id = role.id ",''.$this->table.'.id = "'.$userid.'"');
        return ResultVo::success($user);
    }

}
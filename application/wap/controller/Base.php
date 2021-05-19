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
        $this->check_subscribe();
        
    }
    public function check_subscribe(){

        if($this->request->header('token')){
            if($this->uid){
                $is_user = $this->WeDb->find('user','id = '.$this->uid);
                    if($is_user){

                        $new_uinfo = $this->Wechat_tool->userinfo_openid($is_user['open_id']);
                        if(@$new_uinfo['errcode']){return;}
                        if($new_uinfo['subscribe']==0){
                        // $return_error = ResultVo::error(400, "您未关注公众号，请重试");
                            echo json_encode(['çode'=>400, 'message'=>"您未关注公众号，请重试"],JSON_UNESCAPED_UNICODE);
                            die();
                            exit();

                        }

                    }

       // var_dump($new_uinfo);
       }

        }
        
    }

        /** http get 请求*/
    public  function Gemini_GetReq($url, $param = array()) {
        if (! is_array ( $param )) {
            throw new Exception ( "参数必须为array" );
        }
        $p = '';
        foreach ( $param as $key => $value ) {
            $p = $p . $key . '=' . $value . '&';
        }
        if (preg_match ( '/\?[\d\D]+/', $url )) { // matched ?c
            $p = '&' . $p;
        } else if (preg_match ( '/\?$/', $url )) { // matched ?$
            $p = $p;
        } else {
            $p = '?' . $p;
        }
        $p = preg_replace ( '/&$/', '', $p );
        $url = $url . $p;
        $httph = curl_init ( $url );
        curl_setopt ( $httph, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt ( $httph, CURLOPT_SSL_VERIFYHOST, 2 );
        curl_setopt ( $httph, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)" );
        
        curl_setopt ( $httph, CURLOPT_RETURNTRANSFER, 1 );
        // curl_setopt ( $httph, CURLOPT_HEADER, 1 );
        $rst = curl_exec ( $httph );
        curl_close ( $httph );
        return $rst;
    }
    /**http post 请求 */
    public function Gemini_PostReq($url, $param) {
        // if (! is_array ( $param )) {
        // throw new Exception ( "参数必须为array" );
        // }
        $httph = curl_init ( $url );
        curl_setopt ( $httph, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt ( $httph, CURLOPT_SSL_VERIFYHOST, 2 );
        curl_setopt ( $httph, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)" );
        curl_setopt ( $httph, CURLOPT_POST, 1 ); // 设置为POST方式
        curl_setopt ( $httph, CURLOPT_POSTFIELDS, $param );
        curl_setopt ( $httph, CURLOPT_RETURNTRANSFER, 1 );
        // curl_setopt ( $httph, CURLOPT_HEADER, 1 );
        $rst = curl_exec ( $httph );
        curl_close ( $httph );
        return $rst;
    }
    // public function initialize()
    // {
    //     parent::initialize();
    //     header('Access-Control-Allow-Origin:*');
    //     header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,PATCH,OPTIONS');
    //     header('Access-Control-Allow-Headers:Content-Type, X-ELEME-USERID, X-Eleme-RequestID, X-Shard,X-Shard, X-Eleme-RequestID,X-Adminid,X-Token');
    // }
}

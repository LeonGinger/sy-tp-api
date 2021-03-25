<?php

// require(ROOT_PATH . '/vendor/autoload.php');


use \Yunpian\Sdk\YunpianClient;
// use app\model\SysconfigModel;


class Sms_YunPian {
    protected $apikey;

    public function __construct(){    
        $this->apikey = '1c0fb9d2dc8a0b2cdbed29e596ffc33e';
    }   

    public function send( $mobile , $code ){
        //初始化client,apikey作为所有请求的默认值
        // var_dump($mobile);
        // exit;
        $clnt = YunpianClient::create($this->apikey);
        $param = [YunpianClient::MOBILE => $mobile ,YunpianClient::TEXT => '【智服网】您的验证码是'.$code];
        $r = $clnt->sms()->single_send($param);
        
        try{
            
            if($r->isSucc()){
                
                return true;
            }else{
                return false;
            }
        }catch(Exception $e) {
            
        }
    }



}



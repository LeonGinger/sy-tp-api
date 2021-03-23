<?php

require(Env::get('root_path'). '/vendor/autoload.php');


use \Yunpian\Sdk\YunpianClient;
use app\model\SysconfigModel;


class Sms_YunPian {
    protected $apikey;

    public function __construct(){    
        $this->apikey = "1c0fb9d2dc8a0b2cdbed29e596ffc33e";
    }   

    public function send( $mobile , $code ){
        //初始化client,apikey作为所有请求的默认值
        $clnt = YunpianClient::create($this->apikey);
        $param = [YunpianClient::MOBILE => $mobile ,YunpianClient::TEXT => '【智服网】您的验证码是'.$code];

        try{
            $r = $clnt->sms()->single_send($param);
            if($r->isSucc()){
                // var_dump($r->data());
                return true;
            }else{
                return false;
            }
        }catch(Exception $e) {
            
        }
    }



}



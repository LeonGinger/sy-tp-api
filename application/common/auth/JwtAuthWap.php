<?php
namespace app\common\auth;

use \Firebase\JWT\JWT;

/**
 * 单例 Jwt wap
*/
class JwtAuthWap{

    private $token;
    /**
     * 单例模式 jwtAuth句柄
     */
    private static $instance;

    private $key = "GN_FOREVER";
    private $aud = "GN_INBAN";
    private $iss = "v2.gnleon.xyz";
    private $uid;

    /**
     * decode token 客户端传入的token
     */
    private $decodeToken;

    /**
     * 获取JWT句柄
     * @return JwtAuth
     */
    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance=new self();
        }
        return self::$instance;
    }
    /**
     *  私有化构造函数
     */
    private function __construct()
    {

    }
    /**
     * 私有化clone函数
     */
    private function __clone(){

    }
    /**获取 token*/
    public function getToken(){
        return  (string)$this->token;
   }
   /**设置 tokne */
   public function setToken($token){
       $this->token=$token;
       return $this;
   }
   /**设置 uid */
   public function setUid($uid){
       $this->uid=$uid;
       return $this;
   }  
   public function getUid(){
       return  $this->uid;
   }
    /**
     * 编码JWT token
     */
    public function encode(){
        $time = time();
        $token = [
        	'iss' => $this->iss,
            'iat' => $time, 
            'data' => [ //自定义信息，不要定义敏感信息
             	'userid' => $this->uid,
            ]
        ];
        $access_token['scopes'] = 'role_access'; //token标识，请求接口的token
        $access_token['exp'] = $time+10; //access_token过期时间,这里设置2个小时

        /*可只使用token 不刷新*/
        $refresh_token = $token;
		$refresh_token['scopes'] = 'role_refresh'; //token标识，刷新access_token
        $refresh_token['exp'] = $time+3600; //access_token过期时间,这里设置30天

        $jsonList = [
			'access_token'=>JWT::encode($access_token,$this->key),
			'refresh_token'=>JWT::encode($refresh_token,$this->key),
			'token_type'=>'bearer' //token_type：表示令牌类型，该值大小写不敏感，这里用bearer
        ];
        $this->token = $jsonList['refresh_token'];
        return $this;
    }

    public function decode(){

        try {
            if(!$this->decodeToken){
                $this->decodeToken = $this->getToken();
            }

            JWT::$leeway = 60;//当前时间减去60，把时间留点余地

            $decoded = JWT::decode($this->decodeToken, $this->key, ['HS256']); //HS256方式，这里要和签发的时候对应

            $arr = (array)$decoded;

            $this->uid = $arr['data']->userid;
 
            return $this->decodeToken;
     } catch(\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
         //echo $e->getMessage();
         //return [$e->getCode(),$e->getMessage()];
         return false;
     }catch(\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
         //echo $e->getMessage();
         //return [$e->getCode(),$e->getMessage()];
         return false;
     }catch(\Firebase\JWT\ExpiredException $e) {  // token过期
         //echo $e->getMessage();
         //return [$e->getCode(),$e->getMessage()];
         return false;
    }catch(Exception $e) {  //其他错误
         //echo $e->getMessage();
         //return [$e->getCode(),$e->getMessage()];
         return false;
     }
    }
}
<?php
namespace app\common\utils;
use Naixiaoxin\ThinkWechat\Facade;
/**
 * 微信工具类
 */
class WechatUtils{

    /* 配置在config/wechat.php */
    /**
     * $instance 实例
     */
    private static $instance = null;
    //微信用用accesstoken 
    private static $wxAccessToken = null;
    //token 过期时间
    private static $expires = null;
    //token 到期时间
    private static $valid = null;
    public $app;
    private function __construct(){
        $this->app = Facade::officialAccount();
    }

    /**
     * 单例模式
     */
    static public function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new WechatUtils();
            // //第一个静态实例生成后,赋予初始化的accessToken等变量数据
            self::$instance->getWxToken();
        }
        return self::$instance;
    }
    /**
     * 获取Token
     * return accessToken 返回Token
     */
    public function getWxToken()
    {
        $instance = self::getInstance();
        if ($instance::$wxAccessToken == null) {
            // echo 'create!';
            $instance->get_access_token();
        } else {
            //检测token是否过期
            if (time() > $instance::$valid) {
                // echo 'get new!';
                //已过期-重新请求token
                $instance->get_access_token();
            }
        }
        return $instance::$wxAccessToken;
    }
    /**
     * 请求access_token.
     * */
    public function get_access_token(){
        $instance = self::getInstance();
        $accessToken = $this->app->access_token;
        $token = $accessToken->getToken();
        
        $instance::$wxAccessToken = $token['access_token']; //更新当前token
        $instance::$expires = $token['expires_in']; //更新token有效时间
        $instance::$valid = time() + $instance::$expires; //更新token到期时间
    }   
    /* */
    /**
     * 
     *  测试用
     * 
     * */ 
    public function index(){
        $users = $this->app->user->list();
        var_dump($users);
        exit;
        $listtpl = $app->template_message->getPrivateTemplates();
        var_dump($listtpl);
    }
    /**
     * 发送模板消息-公众号
     * openid-用户openID
     * tpl_id-模板id
     * data-模板字段
     */
    public function send_msg($openid,$tpl_id,$data){
        // $app  = Facade::officialAccount();
        $result = $this->app->template_message->send([
            'touser' => $openid,
            'template_id' => $tpl_id,
            // 'url' => 'http://v2.gnleon.xyz/',
            'url' => $data['url'],
            'data' => $data['row'],
        ]);
        return $result;
    }

    /**
     * 获取用户信息-公众号
     * 测试url
     * https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx695f4c98a1b6cff5&redirect_uri=http://sy.zsicp.com/wap/user/user/set_token&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
     
     *
     */
    public function userinfo(){
        $instance = self::getInstance();
        $new_user = [];
        $user = $this->app->oauth->user();
        $new_user = $user->getOriginal();
        //是否关注
        $user_info = get_by_curl('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$instance::$wxAccessToken.'&openid='.$user->id);
        $user_info = json_decode($user_info);
        $new_user['subscribe'] = $user_info->subscribe;

        return $new_user;
    }

}
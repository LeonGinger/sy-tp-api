<?php

namespace app\common\utils;

use Naixiaoxin\ThinkWechat\Facade;
use think\facade\Config;

/**
 * 微信工具类
 */
class WechatUtils
{

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
    private function __construct()
    {
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
    public function get_access_token()
    {
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
    public function index()
    {
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
    public function send_msg($openid, $tpl_id, $data)
    {
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
     * https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd49aee67b33932b2&redirect_uri=http://sy.zsicp.com/wap/user/user/Login&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
     *
     */
    public function userinfo_oa()
    {
        $instance = self::getInstance();
        $new_user = [];
        $user = $this->app->oauth->user();
        $new_user = $user->getOriginal();
        try {
            //是否关注公众号
            $user_info = get_by_curl('https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $instance::$wxAccessToken . '&openid=' . $user->id);
            $user_info = json_decode($user_info);
            $new_user['subscribe'] = $user_info->subscribe;
        } catch (\Throwable $th) {
            $new_user['subscribe'] = 0;
        }
        return $new_user;
    }
    /*通过openid获取最新微信用户信息*/
    public function userinfo_openid($openid)
    {
        $instance = self::getInstance();
        $user = $this->app->user->get($openid);
        return $user;
    }


    
    // 发送模板消息
    public function sendMessage($data)
    {
        $appid = 'wxd49aee67b33932b2';
        $secret = '7af33c205b5bfe0d4f55ae00995fff0e';
        //模板消息
        $template = array(
            'touser' => $data['openid'],  //用户openid
            'template_id' =>$data['Template_id'], //在公众号下配置的模板id
            'url' => $data['url'], //点击模板消息会跳转的链接
            'topcolor' => "#7B68EE",
            'data' => $data['content'],
        );
        
        $json_template = json_encode($template, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        // var_dump($json_template);
        
        $access_token = $this->get_token($appid, $secret);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
        $result = $this->https_request($url, $json_template);
        // var_dump($result);
        // exit;
        $result = json_decode($result);
        if ($result->errcode > 0) {
            return false;
        }
        return true;
    }
    public function get_token($appid, $secret)
    {
        $token_data = @file_get_contents('wechat_token.txt');
        if (!empty($token_data)) {

            $token_data = json_decode($token_data, true);

            $time  = time() - $token_data['time'];

            if ($time > 3600) {

                $token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";

                $token_res = $this->https_request($token_url);
                $token_res = json_decode($token_res, true);
                $token = $token_res['access_token'];
                $data = [
                    'time' => time(),
                    'token' => $token
                ];
                file_put_contents('wechat_token.txt', json_encode($data));
            } else {

                $token = $token_data['token'];
            }
        } else {
            $token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
            $token_res = $this->https_request($token_url);
            $token_res = json_decode($token_res, true);

            $token = $token_res['access_token'];

            $data = [
                'time' => time(),
                'token' => $token
            ];
            file_put_contents('wechat_token.txt', json_encode($data));
        }

        return $token;
    }
    function https_request($url, $data = null)
    {
        // curl 初始化
        $curl = curl_init();

        // curl 设置
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        // 判断 $data get  or post
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // 执行
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }
}

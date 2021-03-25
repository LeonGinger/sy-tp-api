<?php

namespace app\wap\controller\wechat;

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
class WechatController extends Base
{
  public function index()
  {
    $Config = $this->getConfig();
    $this->assign("Config", $Config);
    return view();
  }
  // 调用微信扫一扫功能
  public function getConfig()
  {
    // 微信 JS 接口签名校验工具: https://mp.weixin.qq.com/debug/cgi-bin/sandbox?t=jsapisign
    $appid = 'wxd49aee67b33932b2';
    $secret = '7af33c205b5bfe0d4f55ae00995fff0e';
    // 获取token
    $token = $this->get_token($appid, $secret);
    // 获取ticket
    $ticket = $this->get_ticket($token);
    // 进行sha1签名

    $timestamp = time();
    $nonceStr = $this->createNonceStr();
    // 注意 URL 建议动态获取(也可以写死).

    // $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    // $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; // 调用JSSDK的页面地址
    $url = $this->request->param('url'); // 前后端分离的, 获取请求地址(此值不准确时可以通过其他方式解决)

    $str = "jsapi_ticket={$ticket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
    $sha_str = sha1($str);

    $data = ['appid' => $appid, 'timestamp' => $timestamp, 'nonceStr' => $nonceStr, 'signature' => $sha_str, 'ticket' => $ticket];
    return ResultVo::success($data);;
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
      // var_dump("2");
      // exit;
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
  function get_ticket($token)
  {
    $ticket_data = @file_get_contents('wechat_ticket.txt');
    if (!empty($ticket_data)) {
      $ticket_data = json_decode($ticket_data, true);
      $time  = time() - $ticket_data['time'];
      if ($time > 3600) {
        $ticket_url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$token}&type=jsapi";
        $ticket_res = $this->https_request($ticket_url);
        $ticket_res = json_decode($ticket_res, true);
        $ticket = $ticket_res['ticket'];

        $data = [
          'time'    => time(),
          'ticket'  => $ticket
        ];
        file_put_contents('wechat_ticket.txt', json_encode($data));
      } else {
        $ticket = $ticket_data['ticket'];
      }
    } else {
      $ticket_url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$token}&type=jsapi";
      $ticket_res = $this->https_request($ticket_url);
      $ticket_res = json_decode($ticket_res, true);
      $ticket = $ticket_res['ticket'];

      $data = [
        'time'    => time(),
        'ticket'  => $ticket
      ];
      file_put_contents('wechat_ticket.txt', json_encode($data));
    }
    return $ticket;
  }
  function createNonceStr($length = 16)
  {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }


  /**
   * 模拟 http 请求
   * @param  String $url  请求网址
   * @param  Array  $data 数据
   */
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
  // 模板消息
  public function send_message($data)
  {
    $appid = 'wxd49aee67b33932b2';
    $secret = '7af33c205b5bfe0d4f55ae00995fff0e';
    //模板消息
    $template = array(
      'touser' => $data['openid'],  //用户openid
      'template_id' => Config::get('YjJLfcctmr0QPiL9B6l9k4C4SFiUakfHaMxa-AiNU9g'), //在公众号下配置的模板id
      'url' => $data['url'], //点击模板消息会跳转的链接
      'topcolor' => "#7B68EE",
      'data' => array(
        'first' => array('value' => $data['title'], 'color' => "#000000"),
        'keyword1' => array('value' => $data['name'], 'color' => '#000000'),  //keyword需要与配置的模板消息对应
        'keyword2' => array('value' => $data['mobile'], 'color' => '#000000'),
        'keyword3' => array('value' => $data['visittime'], 'color' => '#000000'),
        'remark' => array('value' => $data['remark'], 'color' => '#000000'),
      )
    );
    $json_template = json_encode($template, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    // var_dump($json_template);
    $access_token = $this->get_token($appid, $secret);
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
    $result = $this->https_request($url, $json_template);
    $result = json_decode($result);
    if ($result->errcode > 0) {
      return false;
    }
    return true;
  }
  /*自定义 模板*/

  public function send_diymessage($openid, $data, $url = '')
  {
    $appid = 'wxd49aee67b33932b2';
    $secret = '7af33c205b5bfe0d4f55ae00995fff0e';
    //模板消息
    $template = array(
      'touser' => $openid,  //用户openid
      'template_id' => $data['template_id'], //在公众号下配置的模板id
      'url' => $url, //点击模板消息会跳转的链接
      'topcolor' => "#7B68EE",
      //keyword需要与配置的模板消息对应
      'data' => $data['array'],
    );
    $json_template = json_encode($template, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    // var_dump($json_template);
    $access_token = $this->get_token($appid, $secret);
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
    $result = $this->https_request($url, $json_template);
    $result = json_decode($result);

    if ($result->errcode > 0) {
      return false;
    }

    return true;
  }
}

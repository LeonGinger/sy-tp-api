<?php

namespace app\wap\controller\Source;

use app\wap\controller\Base;
use app\common\enums\ErrorCode;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;
use think\facade\Validate;
use think\facade\Config;
use think\route\Resource;
use think\db;
use think\Queue;
use redis\Redis;
use app\model\Business;
use app\model\Menu;

/**
 * 用户相关
 */
class SourceController extends Base
{
  private $table = 'source';
  private $table2 = 'order';
  // 操作员扫码出入库客户查询溯源接口
  public function open_source()
  {
    

    $userid = $this->uid;
    $user = $this->WeDb->find('user',"id={$userid}");
    $code = $this->request->param('source_code');
    if($code == '' || $code == null){
      return ResultVo::error(ErrorCode::DATA_NOT['code'], ErrorCode::DATA_NOT['message']);
    }
    $key = $this->request->param('key');
    $time = date('Y-m-d h:i:s');
    $source = $this->WeDb->find('source', 'source_code ="'. $code .'"');
    if ($key == 1) { // 入库操作
      if ($user['business_notice'] == "" || $user['role_id'] == 4) {
        return ResultVo::error(ErrorCode::OUT_LIMIT_NOT['code'], ErrorCode::OUT_LIMIT_NOT['message']);
      }
      $sc_data = [
        'storage_time' => $time,
        'enter_user_id' => $userid,
        'operator_name' => $user['username'],
      ];
      $update = $this->WeDb->update($this->table, 'source_code = "'. $code .'" ',$sc_data);
      // 推送给操作员↓
      $da_content = [
        'content1' => ['value' => '批次已入库成功', 'color' => "#000000"],
        'time' => ['value' => $time, 'color' => "#000000"],
        'user_name' => ['value' => $user['username'], 'color' => "#000000"],
        'order_number' => ['value' => $source['order_number'], 'color' => "#000000"],
        'remark' => ['value' => '操作成功', 'color' => "#000000"],
      ];
      $data = [
        'Template_id' => 'ZtO1j6z81O_xqjog9OgI9Jnon6gkYn-t3M22fnwNn8A',
        'openid' => $user['open_id'],
        'url' => 'https://sy.zsicp.com/h5/#/pages/operation/operation',
        'content' => $da_content,
      ];
      $return = $this->Wechat_tool->sendMessage($data);
      // * //
    } else if ($key == 2) { // 出库操作
      if($source['enter_user_id'] == null){
        return ResultVo::error(110,"您的操作违规了，请重试!");
      }
      if($user['business_notice'] == "" || $user['role_id'] == 4){
        return ResultVo::error(ErrorCode::OUT_LIMIT_NOT['code'], ErrorCode::OUT_LIMIT_NOT['message']);
      }
      $sc_data = [
        'deliver_time' => $time,
        'out_user_id' => $userid,
      ];
      $update = $this->WeDb->update($this->table, 'source_code = "' . $code . '" ', $sc_data);
      // 推送给操作员↓
      $da_content = [
        'content1' => ['value' => '批次已出库成功', 'color' => "#000000"],
        'time' => ['value' => $time, 'color' => "#000000"],
        'user_name' => ['value' => $user['username'], 'color' => "#000000"],
        'order_number' => ['value' => $source['order_number'], 'color' => "#000000"],
        'remark' => ['value' => '操作成功', 'color' => "#000000"],
      ];
      $data = [
        'Template_id' => 'ZtO1j6z81O_xqjog9OgI9Jnon6gkYn-t3M22fnwNn8A',
        'openid' => $user['open_id'],
        'url' => 'https://sy.zsicp.com/h5/#/pages/operation/operation',
        'content' => $da_content,
      ];
      $return = $this->Wechat_tool->sendMessage($data);
      // * //
    } else if ($key == 3) { // 用户查询溯源信息操作
      if($source['out_user_id'] == null){
        return ResultVo::error(110,"您的操作违规了，请重试!");
      }
      $numberii = $source['order_key_number'];
      $numberi = $source['source_number'];
      $order_number = $source['order_number'];
      $id = $source['id'];

      if ($numberii == null) {
        $numberii = 1;
      } else {
        $numberii += 1;
      }
      if ($numberi == null) {
        $numberi = 1;
      } else {
        $numberi += 1;
      }
      $source_log = db::table('source_log')->where('user_id = '.$userid.' and source_code = "'.$code.'"')->find();
      $remote_info = $this->getIPaddress();
      // var_dump($remote_info["Result"]['ip']);
      // exit();
      if ($source_log == null) {
        $data = [
          'user_id' => $userid,
          'source_code' => $code,
          'menu_id' => $source['menu_id'],
          'track' => 1,
          'track_time' => $time,
          'state' => 1,
          'create_time' => $time,
          'ip'=>$remote_info["Result"]['ip'],
          'longitude'=>$remote_info["Result"]['address']['j'],
          'latitude'=>$remote_info["Result"]['address']['w'],
          'city'=>$remote_info["Result"]['address']['c'],
          'province'=>$remote_info["Result"]['address']['p'],
          'county'=>$remote_info["Result"]['address']['d'],
        ];
        $log_insert = $this->WeDb->insert('source_log', $data);
      }else{
        $remote_info = $this->getIPaddress();
        // var_dump($remote_info);
        // exit;
        $track = $source_log['track'];
        $track += 1;
        $data = [
          'track' => $track,
          'track_time' => $time,
          'ip'=>$remote_info["Result"]['ip'],
          'longitude'=>$remote_info["Result"]['address']['j'],
          'latitude'=>$remote_info["Result"]['address']['w'],
          'city'=>$remote_info["Result"]['address']['c'],
          'province'=>$remote_info["Result"]['address']['p'],
          'county'=>$remote_info["Result"]['address']['d'],
        ];
        $update = $this->WeDb->update('source_log', "id = {$source_log['id']}", $data);
      }
      $source = $this->WeDb->find('source', 'source_code ="'. $code .'"');
      $update1 = $this->WeDb->update($this->table, 'order_number = "' . $order_number . '" ', ['order_key_number' => $numberii]);
      // var_dump($update1);
      // exit;
      $update = $this->WeDb->update($this->table, 'id = "'.$id.'"', ['source_number' => $numberi]);
      $sc_data = [
        'scan_time' => $time,
      ];
      $update = $this->WeDb->update($this->table,'source_code = "'. $code .'"', $sc_data);
      $business = Business::with(['BusinessAppraisal','BusinessImg'])
                ->where("id = {$source['business_id']}")
                ->select();
      $menu = Menu::with(['CertificateMenu','MenuMonitor'])
              ->where("business_id = {$source['business_id']}")
              ->select();
      // var_dump($source['enter_user_id']);
      // exit;
      $source['business']=$business;
      $source['menu']=$menu;
      return ResultVo::success($source);
    }
    return ResultVo::success($update);
  }
  // 查询溯源信息
  public function SelectAll()
  {
    $userid = $this->uid;
    $code = $this->request->param('source_code');
    $source = $this->WeDb->find('source', 'source_code = "' . $code . '" ');
    if($source == null){
      return ResultVo::error('963','您的溯源码错误，请重试');
    }
    $business = Business::where("id = {$source['business_id']}")
                ->select();
    $source['business']=$business;
    if($source['enter_user_id']){
      $pull_user = $this->WeDb->find('user',"id = {$source['enter_user_id']}");
      $source['pull_user'] = $pull_user['username'];
      $source['pull_phone']= $pull_user['phone'];
    }
    $user = $this->WeDb->find('user',"id = {$userid}");
    if($user['business_notice'] == $source['business_id']){
      $source['business_key'] = true;
    }else{
      $source['business_key'] = false;
    }
    return ResultVo::success($source);
  }
  // 出入库记录
  public function opend_list()
  {
    $userid = $this->uid;
    $user = $this->WeDb->find('user', "id = {$userid}");
    $business_id = $user['business_notice'];
    if($user['role_id'] == 3){
      $select = $this->WeDb->selectView($this->table, "enter_user_id = {$userid} and business_id = {$business_id}");
      $select2 = $this->WeDb->selectView($this->table, "out_user_id = {$userid} and business_id = {$business_id}");
      $data = [
        'message' => "请求成功",
        'enter' => $select,
        'out' => $select2,
      ];
    }else if($user['role_id'] == 2){
      $select = $this->WeDb->selectView($this->table,"business_id = {$business_id} and enter_user_id is not null");
      $select2 = $this->WeDb->selectView($this->table,"business_id = {$business_id} and out_user_id is not null");
      $data = [
        'message' => "请求成功",
        'enter' => $select,
        'out' => $select2,
      ];
    }
    return ResultVo::success($data);
  }
  // 新建订单（一次建立多个溯源码）
  public function Add_order()
  {
    $userid = $this->uid;
    $user = $this->WeDb->find('user', "id= {$userid}");
    $business_id = $user['business_notice'];
    $sourceANDnumber = $this->request->param("sourceANDnumber");
    $order_number = round_Code();
    $data = [
      'user_id' => $userid,
      'business_id' => $business_id,
      'order_number' => $order_number,
      'create_time' => date('Y-m-d h:i:s'),
      'source_injson' => json_encode($sourceANDnumber),
    ];
    $orderid = $this->WeDb->insertGetId('order', $data);
    $source_code_array = array();
    for ($i = 0; $i < count($sourceANDnumber); $i++) {  //第一维箱种类
      $menu_id = $sourceANDnumber[$i]['menu_id'];
      $number = $sourceANDnumber[$i]['number']; //多少箱
      $menu_number = $sourceANDnumber[$i]['menu_number']; //一箱有多少个
      $menu = $this->WeDb->find('Menu', "id = {$menu_id}");
      $menu_certificate = $this->WeDb->find('menu_certificate', "menu_id={$menu_id}");
      $menu_monitor = $this->WeDb->find('menu_monitor', "menu_id={$menu_id}");
      $sourceinsert = array();
      for ($o = 0; $o < $number; $o++) {  //第二维多少箱
        $source_code = round_code(14);
        $in_data = [
          'order_id' => $orderid,
          'business_id' => $business_id,
          'menu_id' => $menu_id,
          'order_number' => $order_number,
          'menu_name' => $menu['menu_name'],
          'menu_address' => $menu['menu_address'],
          'menu_weight' => $menu['menu_weight'],
          'production_time' => $menu['production_time'],
          'quality_time' => $menu['quality_time'],
          'menu_images_json' => $menu['menu_images_json'],
          'monitor_image' => $menu_monitor['monitor_image'],
          'test_location' => $menu_monitor['test_location'],
          'sample_name' => $menu_monitor['sample_name'],
          'monitoring_time' => $menu_monitor['monitoring_time'],
          'source_code' => $source_code,
          'certificate_image' => $menu_certificate['certificate_image'],
        ];
        // tp队列生成溯源码
        $jobHandlerClassName  = 'app\wap\controller\job\order';
        $jobQueueName = "createorder";
        $num = 50000;
        $this->redis = new Redis();
        $this->redis->set('has_create',$num);
        $this->redis->set('gotostatus',1);
        $isPushed = Queue::push( $jobHandlerClassName , $in_data , $jobQueueName );
        if( $isPushed !== false ){
          for ($n = 0; $n <= $menu_number; $n++) { //第三维：多少个水果多少个溯源码，因箱子外也有一个码所以是 <=
            $source_code_array[$i][$o][$n] = $source_code; // $i为箱 $o为多少箱 $n为箱中有多少个 溯源码数量= 水果个数 + 外箱子 = $n+1↑
          }
        }
        // *** //
      }
    }
    return ResultVo::success($source_code_array);
  }

  /**
   * [getIP 获取用户IP地址与真实地址]
   * @Param
   * ip IP地址      
    db_type IP数据库代号 1 2 3
    需要查询的数据库代号编号 1 为混合库，2 为纯真库，3 为县区库.  PS 默认为 1  
    cn_to_unicode 汉字Unicode转码 1 0
    本设置是对服务器返回数据时是否对中文汉字进行unicode转码，为增强兼容性，默认以unicode转码 
    token 密匙    您的token密匙 是 
    datatype  返回的数据类型 json
    xml
    json/xml 可选，默认为json格式
   * @DateTime     2021-04-14T13:38:25+0800
   * @LastTime     2021-04-14T13:38:25+0800
   * @return       [type]                        [description]
   * w      纬度  如：22.3712
     j      经度  如：114.0412
     p      省份  如：北京市,广东省,广西省
     c      城市  如：广州市
     d      区,县 
   */
  private function getIPaddress(){
      $ip = $this->request->ip();
      // if($ip=="127.0.0.1"){return false;}
      //测试
      $ip = "120.79.52.222";
      $param = array(
        'ip' => $ip,
        'token' => "485e236f52574f710cac6e25fe1b74f7",
        'db_type' => 1,
        'cn_to_unicode' => 0,
        'datatype' => 'json',

      );
      $url = "api.djapi.cn/ipaddr/get?";
      // foreach ($param as $key => $value) {
      //   # code...
      //   $url = $url.$key."=".$value.'&';
      // }
      $result = $this->Gemini_GetReq($url,$param);
      $result = json_decode($result,true);
      return $result;
  } 
}

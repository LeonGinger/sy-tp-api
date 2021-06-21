<?php

namespace app\wap\controller\user;

use app\wap\controller\Base;
use app\common\enums\ErrorCode;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;
use think\facade\Validate;
use think\facade\Config;
use think\route\Resource;
use redis\Redis;
use think\db;
use app\model\SourceLog;
use app\common\model\auth\AuthRoleAdmin;
use think\facade\Env;

/**
 * 用户相关
 */
class UserController extends Base
{
  private $table = 'user';
  // public function initialize(){

  // }
  public function index()
  {
    var_dump('HALO');
    exit();
  }
  //刷新token
  public function update_token()
  {
    $userid = $this->uid;
    $token = $this->jwtAuthApi->setUid($userid)->encode()->getToken();
    return ResultVo::success($token);
  }
  // 获取一个token
  public function set_token()
  {

//     $filee = file_get_contents(Env::get('root_path')."public\chinaeasy.json");

//     $filee = json_decode($filee,true);
//     $city = [];
//     foreach ($filee as $key => $value) {
//       # code...
//       //var_dump($value['name']);
//       array_push($city, $value['name']);
//     }

// var_dump($city);
//     exit();
//     foreach ($filee['features'] as $key => $value) {
//       var_dump($value['properties']['name']);
//       # code...
//     }
//     //var_dump($filee['features']);
//     exit();
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
    $redis = new Redis();
    $get_uifno = $this->Wechat_tool->userinfo_oa($code);
    if(empty($get_uifno['subscribe'])) {
      return  ResultVo::error(400, "您未关注公众号，请重试");
    }
    $uinfo = $this->WeDb->find($this->table, 'unionid = "' . $get_uifno['unionid'] . '"');
    if ($uinfo) {
      // 更新 有-返回(并替换相关信息)
      $up_data = array(
        // 'username'=>'',
        'gender' => $get_uifno['sex'],
        'subscribe' => $get_uifno['subscribe']
      );
      $this->WeDb->update($this->table, 'id = ' . $uinfo['id'], $up_data);
      $up_authroleadmin  =$this->WeDb->update("auth_role_admin",'admin_id = '.$uinfo['id'],['role_id'=>$uinfo['role_id']]);
    } else {
      // 没-插入  
      $in_data = array(
        'username' => $get_uifno['nickname'],
        'gender' => $get_uifno['sex'],
        // 'phone'=>'',
        'user_image' => $get_uifno['headimgurl'],
        'open_id' => $get_uifno['openid'],
        'role_id' => '4',
        // 'real_name_state'=>'',
        // 'business_notice'=>'',
        'subscribe' => $get_uifno['subscribe'],
        'create_time' => date('Y-m-d H:i:s', time()),
        'unionid' => $get_uifno['unionid'],
        'status' =>1,
        // 'deleteTime'=>'',
      );
      $in_result = $this->WeDb->insertGetId($this->table, $in_data);
      $in_authroleadmin  =$this->WeDb->insertGetId("auth_role_admin",['role_id'=>4,'admin_id'=>$in_result]);
    }
    $uinfo = $this->WeDb->find($this->table, 'open_id = "' . $get_uifno['openid'] . '" and delete_time is null');
    $token = $this->jwtAuthApi->setUid($uinfo['id'])->encode()->getToken();
    $redata = array(
      'uinfo' => $uinfo,
      'token' => $token
    );
    return  ResultVo::success($redata);
  }

  /*用户信息修改保存*/
  public function usave()
  {
    $redis = new Redis();
    $userid = $this->uid;
    $username = $this->request->param('username');
    $user_image = $this->request->param('user_image');
    $phone = $this->request->param('phone');
    // 传入数据不能为空
    if($username == '' || $user_image == ''){
      return ResultVo::error(ErrorCode::DATA_NOT_CONTRNT['code'], ErrorCode::DATA_NOT_CONTRNT['message']);
    }
    $user = $this->WeDb->find('user', "id = {$userid}");
    $data = $this->request->param('phonecode');
    if ($phone != $user['phone']) {
      $code = $redis::get('phonecode_' . $this->uid); // 缓存的验证码
      $inphone = $redis::get('phonecode_' . $this->uid . '_mobile'); // 缓存的手机号
      if ($data != $code || $inphone != $this->request->param('phone')) { // 与传入数据做比对
        return ResultVo::error(ErrorCode::NOT_PHONE_CODE['code'], ErrorCode::NOT_PHONE_CODE['message']);
      }
    }
    $set_data = [
      'username' => $username,
      'user_image' => $user_image,
      'phone' => !empty($phone) ? $phone: '',
    ];
    $result = $this->WeDb->update('user', "id = {$userid}", $set_data);
    return  ResultVo::success($result);
  }
  /**
   * 上传头像图片
   * @remark:前端公用的上传图片接口已在file控制内,本接口遗留了几处地方公共了该接口上传,请转向新的公用图片接口
  */
  public function upload_headimg()
  {
    $file = request()->file('imgurl');  
    // var_dump($file);
    // exit;
    if($file == null){
      return ResultVo::error(ErrorCode::UPLOAD_IS_NULL);
    } 
    $info = $file->validate(['ext' => 'jpg,jpeg,png'])
      ->move(Config::get('upload_headimg_path'));
      // var_dump($file);
      // exit;
    $source = Config::get('upload_headimg_path').$info->getSaveName();
    // $url = Config::get('domain_http').''.$info->getSaveName();
    $url  = str_replace(Config::get('upload_headimg_path'), Config::get('domain_http') . 'uploads/headimg/', $source);
    /**
     * 大小压缩
     * code....  
     **/
    /*返回头像地址 */
    $re_data = array(
      'link' => $url,
      'dir' => $source,
    );
    return ResultVo::success($re_data);
  }
  // 查询当前用户信息
  public function this_user()
  {
    $userid = $this->uid;
    $user = $this->WeDb->selectlink($this->table, 'role', "{$this->table}.role_id = role.id ", '' . $this->table . '.id = "' . $userid . '"');

    if(!$user){return ResultVo::error(406,"查询不到此用户，请退出重试");}
    if($user[0]['business_notice'] != '' && $user[0]['business_notice']!= null){
      $business = $this->WeDb->find('business',"id={$user[0]['business_notice']}");
      $user[0]['business'] = $business; 
      $business_appraisal = $this->WeDb->find('business_appraisal',"id = {$business['business_appraisal_id']}");
      $user[0]['business_appraisal'] = $business_appraisal;
    }
    return ResultVo::success($user[0]);
  }
  // 溯源历史
  public function this_source_log()
  {
    $userid = $this->uid;
    $log = SourceLog::with('Source')->where("user_id = {$userid}")->order("track_time asc")->select();
    return ResultVo::success($log);
  }
  // 短信验证码
  // H5-公共短信发送方法—
  public function iphone_code()
  {
    $redis = new Redis();
    $mobile = $this->request->param('mobile');
    $type  = $this->request->param('type');
    $no_checkrepeat = ['example'];

    if (empty($mobile)) {
      return ResultVo::error(ErrorCode::PHONE_IS_NULL['code'], ErrorCode::PHONE_IS_NULL['message']);
    }

    $check_repeat = $this->WeDb->find('user', 'phone = "' . $mobile . '" and id !='.$this->uid);
    if ($check_repeat) {
      return ResultVo::error(ErrorCode::PHONE_IS_TWO['code'], ErrorCode::PHONE_IS_TWO['message']);
    }

    // Loader::import('Sms_YunPian', EXTEND_PATH);
    // var_dump($check_repeat);
    // exit;
    $sms = new \Sms_YunPian();

    $code = mt_rand(100000, 999999);
    // 测试
    // $code = '123456';
    // $redis::set('phonecode_'.$this->uid,$code,180);
    // $redis::set('phonecode_'.$this->uid.'_mobile',$mobile,180);
    // return ResultVo::success(['message'=>'发送短信验证成功','code'=>200]);
    if ($sms->send($mobile, $code)) {
      //设置cookie
      // Cookie::set('Cookie_Message_'.$this->uid ,$code,1200);
      $redis::set('phonecode_' . $this->uid, $code, 300);
      $redis::set('phonecode_' . $this->uid . '_mobile', $mobile, 300);
      //返回结果        
      return ResultVo::error(200,'发送短信验证成功');
    } else { 
      return ResultVo::error(400,'发送短信验证失败');
    }
  }
  //商家入驻发送验证码
  public function business_sendcode(){
    $redis = new Redis();
    $mobile = $this->request->param('mobile');
    $type  = $this->request->param('type');
    $no_checkrepeat = ['example'];

    if (empty($mobile)) {
      return ResultVo::error(ErrorCode::PHONE_IS_NULL['code'], ErrorCode::PHONE_IS_NULL['message']);
    }

    $check_repeat = $this->WeDb->find('user', 'phone = "' . $mobile . '" and id !='.$this->uid);
    if ($check_repeat) {
      return ResultVo::error(ErrorCode::PHONE_IS_TWO['code'], ErrorCode::PHONE_IS_TWO['message']);
    }

    $sms = new \Sms_YunPian();
    $code = mt_rand(100000, 999999);
    // 测试
    // $code = '123456';
    // $redis::set('phonecode_applybusiness_' . $this->uid, $code, 180);
    // $redis::set('phonecode_applybusiness_' . $this->uid . '_mobile', $mobile, 180);
    // return ResultVo::success(['message'=>'发送短信验证成功','code'=>200]);
    if ($sms->send($mobile, $code)) {

      $redis::set('phonecode_applybusiness_' . $this->uid, $code, 300);
      $redis::set('phonecode_applybusiness_' . $this->uid . '_mobile', $mobile, 300);
      //返回结果        
      return ResultVo::error(200,'发送短信验证成功');
    } else { 
      return ResultVo::error(400,'发送短信验证失败');
    }
  }



  // 常见问题查询接口
  public function common_problem(){
    $select = db::table('common_problem')->select();
    return ResultVo::success($select);
  }
  // 商家须知查询接口
  public function business_notice(){
    $select = db::table('business_notice')->where('id = 1')->select();
    return ResultVo::success($select);
  }
  // public function subscribeMassage(){
  //   // 推送给商家的所有人员↓
  //     $da_content = [
  //       'character_string1'=>['value' => '11', 'color' => "#000000"],
  //       'shing3'=>['value' => '11', 'color' => "#000000"],
  //       'time2'=>['value' => '11', 'color' => "#000000"],
  //       'thing5'=>['value' => '11', 'color' => "#000000"],
  //     ];
  //     $data = [
  //       'Template_id'=>'JV0X9Q-mZCaGzks5RK8bDrzkZjWKYS1nKVkN44JPE4U',
  //       'openid'=>'ozY535toP9r_khydwW5SBiV0g-CM',
  //       'url'=>Config::get('domain_h5').'#/pages/Product/Product-list',
  //       'title'=>'123456',
  //       'content'=>$da_content,
  //     ];
  //     $return = $this->Wechat_tool->subscribeMassage($data);
  //   // * //
  // }
}

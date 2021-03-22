<?php

namespace app\wap\controller\Source;

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
class SourceController extends Base
{
    private $table = 'source';
    private $table2 = 'order';
    // 操作员扫码出入库接口
    public function open_source(){
      $userid = $this->uid;
      $user = $this->WeDb->find('User',"id= {$userid}"); 
      $code = $this->request->param('source_code');
      $key = $this->request->param('key');
      if($user['business_notice'] == "" || $user['role_id'] == 4){
        return "对不起，您无权限操作出入库";
      }
      
        $source = $this->WeDb->find('source','source_code = "'.$code.'" ');
        if($key == 1){
          $sc_data = [
            'storage_time'=>date('Y-m-d h:i:s'),
            'enter_user_id'=>$userid,
          ];
          $update = $this->WeDb->update($this->table,'source_code = "'.$code.'" ',$sc_data);
        }
        else if($key == 2){
          $sc_data = [
            'deliver_time'=>date('Y-m-d h:i:s'),
            'out_user_id'=>$userid,
          ];
          $update = $this->WeDb->update($this->table,'source_code = "'.$code.'" ',$sc_data);
        }else if($key == 3){
          $numberii = $source['order_key_number'];
          if($numberii=>)
          $sc_data = [
            'scan_time'=>date('Y-m-d h:i:s'),
            'order_key_number'=>$userid,
          ];
          $update = $this->WeDb->update($this->table,'source_code = "'.$code.'" ',$sc_data);
          return ResultVo::success($source);
        }
        return ResultVo::success($update);
    }
    // 查询溯源信息
    public function SelectAll(){
      $userid = $this->uid;
      $code = $this->request->param('source_code');
      $source = $this->WeDb->find('source','source_code = "'.$code.'" ');
      return ResultVo::success($source);
    }
    // 出入库记录
    public function opend_list(){
      $userid = $this->uid;
      $user = $this->WeDb->find('user',"id = {$userid}");
      $business_id = $user['business_notice'];
      $select = $this->WeDb->selectView($this->table,"enter_user_id = {$userid} and business_id = {$business_id}");
      // exit;
      $select2 = $this->WeDb->selectView($this->table,"out_user_id = {$userid} and business_id = {$business_id}");
      $data = [
        'message'=>"请求成功",
        'enter'=>$select,
        'out'=>$select2,
      ];
      return ResultVo::success($data);
    }
    // 新建订单（一次建立多个溯源码）
    public function Add_order(){
      $userid = $this->uid;
      $user = $this->WeDb->find('User',"id= {$userid}");
      $business_id = $user['business_notice'];
      $sourceANDnumber = $this->request->param("sourceANDnumber");
      $order_number = round_Code();
      $data = [
        'user_id'=>$userid,
        'business_id'=>$business_id,
        'order_number'=>$order_number,
        'create_time'=>date('Y-m-d h:i:s'),
        'source_injson'=>json_encode($sourceANDnumber),
      ];
      $orderid = $this->WeDb->insertGetId('order',$data);
      // exit;
      $source_code_array = array();
      for($i=0;$i<count($sourceANDnumber);$i++){  //第一维度箱种类
        $menu_id = $sourceANDnumber[$i]['menu_id'];
        $number = $sourceANDnumber[$i]['number'];//多少箱
        $menu_number = $sourceANDnumber[$i]['menu_number'];//一箱有多少个
        $menu = $this->WeDb->find('Menu',"id = {$menu_id}");
        $menu_certificate = $this->WeDb->find('menu_certificate',"menu_id={$menu_id}");
        $menu_monitor = $this->WeDb->find('menu_monitor',"menu_id={$menu_id}");
        $sourceinsert = array();
        for($o=0;$o<$number;$o++){  //第二维多少箱
          $source_code = round_code(14);
          $in_data = [
            'order_id'=>$orderid,
            'business_id'=>$business_id,
            'menu_id'=>$menu_id,
            'order_number'=>$order_number,
            'menu_name'=>$menu['menu_name'],
            'menu_address'=>$menu['menu_address'],
            'menu_weight'=>$menu['menu_weight'],
            'production_time'=>$menu['production_time'],
            'quality_time'=>$menu['quality_time'],
            'menu_images_json'=>$menu['menu_images_json'],
            'monitor_image'=>$menu_monitor['monitor_image'],
            'test_location'=>$menu_monitor['test_location'],
            'sample_name'=>$menu_monitor['sample_name'],
            'monitoring_time'=>$menu_monitor['monitoring_time'],
            'source_code'=>$source_code,
            'certificate_image'=>$menu_certificate['certificate_image'],
          ];
          $sourceinsert = $this->WeDb->insertGetId('Source',$in_data);
          if($sourceinsert){
            $source_code_array[$i][$o] = $source_code; // $i为箱 $o为多少箱 $e为箱中有多少个
          } 
        }
      }
      return ResultVo::success($source_code_array);
      // $insert = $this->WeDb->insertGetId('order',['source_injson' => $sourceANDnumber]);
      // // var_dump($sourceANDnumber);
      // return ResultVo::success($sourceANDnumber);
    }
}  
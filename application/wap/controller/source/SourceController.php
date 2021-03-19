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
    private $table = 'Source';
    private $table2 = 'order';
    // 操作员扫码出入库接口
    public function oped_source(){
      $userid = $this->uid;
      $code = $this->request->param('source_code');
    }
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
      $source_code_array = [];
      
      for($i=0;$i<count($sourceANDnumber);$i++){
        $menu_id = $sourceANDnumber[$i]['menu_id'];
        $number = $sourceANDnumber[$i]['number'];
        
        $menu = $this->WeDb->find('Menu',"id = {$menu_id}");
        $menu_certificate = $this->WeDb->find('menu_certificate',"menu_id={$menu_id}");
        for($o=0;$o<$number;$o++){
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
            'source_code'=>$source_code,
            'certificate_image'=>$menu_certificate['certificate_image'],
          ];
          $sourceinsert = $this->WeDb->insertGetId('Source',$in_data);
          if($sourceinsert){
            $source_code_array[] = $source_code;
          }
        }
        return ResultVo::success($source_code_array);
      }
      // $insert = $this->WeDb->insertGetId('order',['source_injson' => $sourceANDnumber]);
      // // var_dump($sourceANDnumber);
      // return ResultVo::success($sourceANDnumber);
    }
    
    

}
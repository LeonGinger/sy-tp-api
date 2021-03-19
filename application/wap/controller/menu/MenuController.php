<?php

namespace app\wap\controller\Menu;

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
class MenuController extends Base
{
    private $table = 'Menu';
    //新建商品接口
    function create_menu(){
      $userid = $this->uid;
      $find = $this->WeDb->find('User',"id={$userid}");
      $businessid = $find['business_notice'];
      $menu_name = $this->request->param('menu_name');
      $menu_address = $this->request->param('menu_address');
      $menu_weight = $this->request->param('menu_weight');
      $production_time = $this->request->param('production_time');
      $quality_time = $this->request->param('quality_time');
      $menu_money = $this->request->param('menu_money');
      $menu_images_json = $this->request->param('menu_images_json');
      // $menu_images_json = ['123123123'=>123456,];
      $date = date('Y-m-d h:i:s');
      $insert = [
        'business_id'=>$businessid,
        'menu_name'=>$menu_name,
        'menu_address'=>$menu_address,
        'menu_weight'=>$menu_weight,
        'production_time'=>$production_time,
        'quality_time'=>$quality_time,
        'menu_money'=>$menu_money,
        'menu_images_json'=>$menu_images_json,
        'create_time'=>$date,
        'update_time'=>$date,
        'update_user_id'=>$userid,
      ];
      $menuid = $this->WeDb->insertGetId($this->table,$insert);
      $monitor_image = $this->request->param('monitor_image');
      $sample_name = $this->request->param('sample_name');
      $monitoring_time = $this->request->param('monitoring_time');
      $test_location = $this->request->param('test_location');
      $insert2 = [
        'menu_id'=>$menuid,
        'monitor_image'=>$monitor_image,
        'sample_name'=>$sample_name,
        'monitoring_time'=>$monitoring_time,
        'test_location'=>$test_location,
      ];
      $menu_monitor = $this->WeDb->insertGetId('menu_monitor',$insert2);
      $certificate_image = $this->request->param('certificate_image');
      $insert3 = [
        'menu_id'=>$menuid,
        'certificate_image'=>$certificate_image,
        'certificate_menu_name'=>$menu_name,
      ];
      $certificate = $this->WeDb->insert('menu_certificate',$insert3);
      return ResultVo::success($certificate);
    }
    // 软删除此商品
    function delete_menu(){
      $menu_id = $this->request->param('menu_id');
      $menuDelete = $this->WeDb->update($this->table,"id = {$menu_id}",['if_delete'=>1]);
      return ResultVo::success($menuDelete);
    }
    // 修改商品
    function update_menu(){
      // exit;
      $userid = $this->uid;
      $menu_id = $this->request->param('menu_id');
      $menu_name = $this->request->param('menu_name');
      $menu_address = $this->request->param('menu_address');
      $menu_weight = $this->request->param('menu_weight');
      $production_time = $this->request->param('production_time');
      $quality_time = $this->request->param('quality_time');
      $menu_money = $this->request->param('menu_money');
      $menu_images_json = $this->request->param('menu_images_json');
      // $menu_images_json = ['123123123'=>123456,];
      $date = date('Y-m-d h:i:s');
      
      $update = [
        'menu_name'=>$menu_name,
        'menu_address'=>$menu_address,
        'menu_weight'=>$menu_weight,
        'production_time'=>$production_time,
        'quality_time'=>$quality_time,
        'menu_money'=>$menu_money,
        'menu_images_json'=>$menu_images_json,
        'update_time'=>$date,
        'update_user_id'=>$userid,
      ];
      // $menuid = $this->WeDb->update($this->table,"id = {$menu_id}",$update);
      
      $monitor_image = $this->request->param('monitor_image');
      $sample_name = $this->request->param('sample_name');
      $monitoring_time = $this->request->param('monitoring_time');
      $test_location = $this->request->param('test_location');
      $update2 = [
        'monitor_image'=>$monitor_image,
        'sample_name'=>$sample_name,
        'monitoring_time'=>$monitoring_time,
        'test_location'=>$test_location,
      ];
      $menu_monitor = $this->WeDb->update('menu_monitor',"menu_id = {$menu_id}",$update2);
      // exit;
      $certificate_image = $this->request->param('certificate_image');
      $update3 = [
        'certificate_image'=>$certificate_image,
        'certificate_menu_name'=>$menu_name,
      ];
      $certificate = $this->WeDb->update('menu_certificate',"menu_id = {$menu_id}",$update3);
      return ResultVo::success($certificate);
    }
}
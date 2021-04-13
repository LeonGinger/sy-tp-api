<?php

namespace app\wap\controller\Menu;

use app\wap\controller\Base;
use app\common\enums\ErrorCode;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;
use think\facade\Validate;
use think\facade\Config;
use think\route\Resource;
use app\model\Menu;

/**
 * 用户相关
 */
class MenuController extends Base
{
    private $table = 'menu';
    //新建商品接口
    public function create_menu(){
      $userid = $this->uid;
      $user = $this->WeDb->find('user', "id={$userid}");
      $business = $this->WeDb->find('business',"id = {$user['business_notice']}");
      if( $business['state'] != 1){
          return ResultVo::error(ErrorCode::STATE_NOT['code'], ErrorCode::STATE_NOT['message']);
      }
      $businessid = $user['business_notice'];
      $menu_name = $this->request->param('menu_name');
      $menu_address = $this->request->param('menu_address');
      $menu_weight = $this->request->param('menu_weight');
      $production_time = $this->request->param('production_time');
      $quality_time = $this->request->param('quality_time');
      $menu_money = $this->request->param('menu_money');
      $menu_images_json = $this->request->param('menu_images_json');
      if($menu_name == null ||$menu_address == null ||$menu_weight == null ||
         $production_time == null ||$quality_time == null ||$menu_money == null ||
         $menu_images_json == null){
            return ResultVo::error(ErrorCode::UPLOAD_IS_NULL['code'],ErrorCode::UPLOAD_IS_NULL['message']);
      }
      // var_dump($menu_images_json);
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
        'menu_images_json'=>json_encode($menu_images_json),
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
        'monitor_image'=>json_encode($monitor_image),
        'sample_name'=>$sample_name,
        'monitoring_time'=>$monitoring_time,
        'test_location'=>$test_location,
      ];
      $menu_monitor = $this->WeDb->insertGetId('menu_monitor',$insert2);
      $certificate_image = $this->request->param('certificate_image');
      $insert3 = [
        'menu_id'=>$menuid,
        'certificate_image'=>json_encode($certificate_image),
        'certificate_menu_name'=>$menu_name,
      ];
      $certificate = $this->WeDb->insert('menu_certificate',$insert3);
      // 推送给商家的所有人员↓
      $foruser = $this->WeDb->selectView('user',"business_notice = {$businessid}");
      for($i=0;$i<count($foruser);$i++){
        $da_content = [
          'content1'=>['value' => '新建商品成功', 'color' => "#000000"],
          'content2'=>['value' => "新建商品名称：{$menu_name}", 'color' => "#000000"],
          'content3'=>['value' => "新建人员：{$user['username']}", 'color' => "#000000"],
          'content4'=>['value' => "新建时间：{$date}", 'color' => "#000000"],
        ];
        $data = [
            'Template_id'=>'yjBbDx1gMpOoBXbs8nMrz5tRbVL28lJ9sRWvvrW6HJo',
            'openid'=>$foruser[$i]['open_id'],
            'url'=>'https://sy.zsicp.com/h5/#/pages/Product/Product-list',
            'content'=>$da_content,
        ];
        $return = $this->Wechat_tool->sendMessage($data);
      }
      // * //
      return ResultVo::success($certificate);
    }
    // 软删除此商品
    public function delete_menu(){
      $userid = $this->uid;
      $user = $this->WeDb->find('user', "id={$userid}");
      $business = $this->WeDb->find('business',"id = {$user['business_notice']}");
      if( $business['state'] != 1){
          return ResultVo::error(ErrorCode::STATE_NOT['code'], ErrorCode::STATE_NOT['message']);
      }
      $businessid = $user['business_notice'];
      $menu_id = $this->request->param('menu_id');
      $menu = $this->WeDb->find('menu',"id = {$menu_id}");
      $menu_name = $menu['menu_name'];
      $date = date('Y-m-d h:i:s');
      $menuDelete = $this->WeDb->update($this->table,"id = {$menu_id}",['if_delete'=>1]);
      // 推送给商家的所有人员↓
      $foruser = $this->WeDb->selectView('user',"business_notice = {$businessid}");
      for($i=0;$i<count($foruser);$i++){
        $da_content = [
          'content1'=>['value' => '删除商品成功', 'color' => "#000000"],
          'content2'=>['value' => "删除商品名称：{$menu_name}", 'color' => "#000000"],
          'content3'=>['value' => "删除人员：{$user['username']}", 'color' => "#000000"],
          'content4'=>['value' => "删除时间：{$date}", 'color' => "#000000"],
        ];
        $data = [
            'Template_id'=>'yjBbDx1gMpOoBXbs8nMrz5tRbVL28lJ9sRWvvrW6HJo',
            'openid'=>$foruser[$i]['open_id'],
            'url'=>'https://sy.zsicp.com/h5/#/pages/Product/Product-list',
            'content'=>$da_content,
        ];
        $return = $this->Wechat_tool->sendMessage($data);
      }
      // * //
      return ResultVo::success($menuDelete);
    }
    // 查询单个商品
    public function find_menu(){
      $userid = $this->uid;
      $user = $this->WeDb->find('user', "id={$userid}");
      $business = $this->WeDb->find('business',"id = {$user['business_notice']}");
      if( $business['state'] != 1){
          return ResultVo::error(ErrorCode::STATE_NOT['code'], ErrorCode::STATE_NOT['message']);
      }
      $menu_id = $this->request->param('');
      // $menu = $this->WeDb->find('menu',"id = {$menu_id['menu_id']}");
      $menu = Menu::with(['MenuMonitor','MenuCertificate'])->where("id = {$menu_id['menu_id']}")->find();
      $menu['business'] = $this->WeDb->find('business',"id = {$menu['business_id']}");
      $menuAll = Menu::with(['MenuMonitor','MenuCertificate'])->where("business_id = {$menu['business_id']}")->select();
      $menu['menuAll'] = $menuAll;
      return ResultVo::success($menu);
    }
    // 修改商品
    public function update_menu(){
      // exit;
      $userid = $this->uid;
      $user = $this->WeDb->find('user', "id={$userid}");
      $business = $this->WeDb->find('business',"id = {$user['business_notice']}");
      if( $business['state'] != 1){
          return ResultVo::error(ErrorCode::STATE_NOT['code'], ErrorCode::STATE_NOT['message']);
      }
      $businessid = $user['business_notice'];
      $menu_id = $this->request->param('menu_id');
      $Y_menu = $this->WeDb->find('menu',"id = {$menu_id}");
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
        'menu_images_json'=>json_encode($menu_images_json),
        'update_time'=>$date,
        'update_user_id'=>$userid,
      ];
      $menuid = $this->WeDb->update($this->table,"id = {$menu_id}",$update);
      $monitor_image = $this->request->param('monitor_image');
      $sample_name = $this->request->param('sample_name');
      $monitoring_time = $this->request->param('monitoring_time');
      $test_location = $this->request->param('test_location');
      $update2 = [
        'monitor_image'=>json_encode($monitor_image),
        'sample_name'=>$sample_name,
        'monitoring_time'=>$monitoring_time,
        'test_location'=>$test_location,
      ];
      $menu_monitor = $this->WeDb->update('menu_monitor',"menu_id = {$menu_id}",$update2);
      // exit;
      $certificate_image = $this->request->param('certificate_image');
      $update3 = [
        'certificate_image'=>json_encode($certificate_image),
        'certificate_menu_name'=>$menu_name,
      ];
      $certificate = $this->WeDb->update('menu_certificate',"menu_id = {$menu_id}",$update3);
      // 推送给商家的所有人员↓
      $foruser = $this->WeDb->selectView('user',"business_notice = {$businessid}");
      for($i=0;$i<count($foruser);$i++){
        $da_content = [
          'content1'=>['value' => '修改商品成功', 'color' => "#000000"],
          'content2'=>['value' => "修改商品名称：{$Y_menu['menu_name']}->{$menu_name}", 'color' => "#000000"],
          'content3'=>['value' => "修改人员：{$user['username']}", 'color' => "#000000"],
          'content4'=>['value' => "修改时间：{$date}", 'color' => "#000000"],
        ];
        $data = [
            'Template_id'=>'yjBbDx1gMpOoBXbs8nMrz5tRbVL28lJ9sRWvvrW6HJo',
            'openid'=>$foruser[$i]['open_id'],
            'url'=>'https://sy.zsicp.com/h5/#/pages/Product/Product-list',
            'content'=>$da_content,
        ];
        $return = $this->Wechat_tool->sendMessage($data);
      }
      // * //
      $return = [
        'update'=>$menuid,
        'update2'=>$menu_monitor,
        'update3'=>$certificate,
      ];
      return ResultVo::success($certificate);
    }
}
<?php

namespace app\wap\controller\business;

use app\wap\controller\Base;
use think\Facade\Cache;
use app\common\enums\ErrorCode;
use app\common\utils\PublicFileUtils;
use app\common\utils\WechatUtils;
use app\common\vo\ResultVo;
use Workerman\Events\Select;
use think\db;
use app\model\Business;
use app\model\Business_notice;
use app\wap\controller\wechat\WechatController;

/**
 * 商家相关
 */
class BusinessController extends Base
{

    private $table = 'business';
    // 加入商家
    public function Apply_add()
    {
        $userid = $this->uid;
        $user = $this->WeDb->find('user',"id={$userid}");
        if($user['role_id'] == 2){
            return "sorry,您同时只能拥有一家企业";
        }
        $code = round_code(16);
        $appraisal_img = $this->request->param('appraisal_img');
        $appraisal = $this->WeDb->insertGetId('business_appraisal',['appraisal_image' => $appraisal_img]);
        $re_data = array(
            'business_name'=>$this->request->param('business_name'),
            'business_address'=>$this->request->param('business_address'),
            'responsible_name'=>$this->request->param('responsible_name'),
            'responsible_phone'=>$this->request->param('responsible_phone'),
            'create_time'=>date('Y-m-d h:i:s'),
            'state'=>1,
            'business_appraisal_id'=>$appraisal,
            // 'business_introduction'=>$this->request->param('business_introduction'),
            'verify_if'=> 3,
            'grant_code'=>$code,
            'img_info'=>0,
        );
        $res = $this->WeDb->insertGetId($this->table,$re_data);
        $img_date = [
            'business_id'=>$res,
            'business_image_injson'=>' ',
            'business_img_contentjson'=>' ',
        ];
        $insert = $this->WeDb->insertGetId('business_img',$img_date);
        // var_dump($this->uid);
        // exit ;
        $ue_data = [
            'business_notice'=>$res,
            'role_id'=>2,
        ];
        $userupdate = $this->WeDb->update('user',"id={$this->uid}",$ue_data);
        return ResultVo::success($userupdate);
    }
    // 商家软删除
    public function Apply_delete()
    {
        $userid = $this->uid;
        $businessid = $this->request->param('business_id');
        $user = $this->WeDb->find('user',"id={$userid}");
        if($user['role_id'] != 2 && $user['role_id'] != 1){
            return "抱歉，您越权了";
        }
        $delete = $this->WeDb->update($this->table,"id={$businessid}",['delete_time'=>date('Y-m-d h:i:s')]);
        $roleupdate = $this->WeDb->update('user',"business_notice={$businessid}",['role_id'=>4,'business_notice'=>'']);
        return ResultVo::success($roleupdate);
    }
    // 商家查询接口all
    public function Apply_selectall()
    {
        $userid = $this->uid;
        $user = $this->WeDb->find('user',"id={$userid}");
        if($user['role_id'] != 3 && $user['role_id'] != 2 && $user['role_id'] != 1){
            return "抱歉，您越权了";
        }
        $businessid = $user['business_notice'];
        $select = business::with('BusinessAppraisal')
                // ->with('BusinessImg')
                ->join('business_img','business_img.business_id = business.id')
                ->where("business.id={$businessid} and business.delete_time is null")
                ->select();
        return ResultVo::success($select);
    }
    // 修改所有信息接口(会修改状态为未审核)
    public function Apply_updateall(){
        $userid = $this->uid;
        $user = $this->WeDb->find('user',"id={$userid}");
        $business_id = $user['business_notice'];
        // $img_key = $this->request->param('img_key');
        // $business_img_id_json  = $this->request->param('$business_img_id_json');
        $business_name = $this->request->param('business_name');
        $responsible_name = $this->request->param('responsible_name');
        $responsible_phone = $this->request->param('responsible_phone');
        $business_address = $this->request->param('business_address');
        $business_images = $this->request->param('business_images');
        $business_introduction = $this->request->param('business_introduction');
        $business_images_injson = $this->request->param('business_images_injson');
        $business_img_contentjson = $this->request->param('business_img_contentjson');
        $appraisal_image = $this->request->param('appraisal_image'); 
        $data = [
            'business_name'=>$business_name,
            'responsible_name'=>$responsible_name,
            'responsible_phone'=>$responsible_phone,
            'business_address'=>$business_address,
            'business_images'=>$business_images,
            'business_introduction'=>$business_introduction,
            'verify_if'=>3,
        ];
        $update = $this->WeDb->update($this->table,"id = {$business_id}",$data);
        $img_data = [
            'business_image_injson'=>$business_images_injson,
            'business_img_contentjson'=>$business_img_contentjson,
        ];
        $update2 = $this->WeDb->update('business_img',"business_id = {$business_id}",$img_data);
        $ap_data = [
            'appraisal_image'=>$appraisal_image
        ];
        $business = $this->WeDb->find($this->table,"id = {$business_id}");
        $update3 = $this->WeDb->update('business_appraisal',"id = {$business['business_appraisal_id']}",$ap_data);
        $data = [
            'update1'=>$update,
            'update2'=>$update2,
            'update3'=>$update3,
        ];
        return ResultVo::success($data);

    }
    // 修改部分信息接口(不会修改状态为未审核)
    public function Apply_update(){
        $userid = $this->uid;
        $user = $this->WeDb->find('user',"id={$userid}");
        $business_id = $user['business_notice'];
        // $img_key = $this->request->param('img_key');
        // $business_img_id_json  = $this->request->param('$business_img_id_json');
        // $business_name = $this->request->param('business_name');
        // $responsible_name = $this->request->param('responsible_name');
        // $responsible_phone = $this->request->param('responsible_phone');
        // $business_address = $this->request->param('business_address');
        $business_images = $this->request->param('business_images');
        $business_introduction = $this->request->param('business_introduction');
        $business_images_injson = $this->request->param('business_images_injson');
        $business_img_contentjson = $this->request->param('business_img_contentjson');
        // $appraisal_image = $this->request->param('appraisal_image'); 
        $data = [
            // 'business_name'=>$business_name,
            // 'responsible_name'=>$responsible_name,
            // 'responsible_phone'=>$responsible_phone,
            // 'business_address'=>$business_address,
            'business_images'=>$business_images,
            'business_introduction'=>$business_introduction,
            // 'verify_if'=>3,
        ];
        $update = $this->WeDb->update($this->table,"id = {$business_id}",$data);
        $img_data = [
            'business_image_injson'=>$business_images_injson,
            'business_img_contentjson'=>$business_img_contentjson,
        ];
        $update2 = $this->WeDb->update('business_img',"business_id = {$business_id}",$img_data);
        // $ap_data = [
        //     'appraisal_image'=>$appraisal_image
        // ];
        // $business = $this->WeDb->find($this->table,"id = {$business_id}");
        // $update3 = $this->WeDb->update('business_appraisal',"id = {$business['business_appraisal_id']}",$ap_data);
        $data = [
            'update1'=>$update,
            'update2'=>$update2,
            // 'update3'=>$update3,
        ];
        return ResultVo::success($data);
    }
    // 移除员工
    public function out_my_user(){
        $userid = $this->uid;
        $out_id = $this->request->param('out_user_id');
        $user = $this->WeDb->find('user',"id = {$userid}");
        if($user['role_id'] !=2 && $user['role_id'] !=1){
            return "抱歉，您越权了";
        }
        $out_data = [
            'role_id'=>4,
            'business_notice'=>''
        ];
        $update = $this->WeDb->update('user',"id = {$out_id}",$out_data);
        return ResultVo::success($update);
    }
    /**
     * 证书上传
     */
    public function Upload()
    {


    }
    // 根据token查询当前用户的商家信息
    public function info(){
        $userid = $this->uid;
        $user = $this->WeDb->selectSQL('user',"where id={$userid} and delete_time is null","*");
        $business = $this->WeDb->find($this->table,"id={$user['business_notice']}");
        return ResultVo::success($business);
    }
    // 二维码授权操作员
    public function join_my(){
        $userid = $this->uid;
        $grant_code = $this->request->param('grant_code');
        $select = $this->WeDb->selectSQL($this->table,'where grant_code="'.$grant_code.'"','id');
        // var_dump($select);
        // exit;
        $selectid =  $select[0]['id'];
        $us_data = [
            'business_notice'=>(int)$selectid,        
            'role_id'=>3,
        ];
        $user = $this->WeDb->find('user',"id={$userid}");
        if($user['role_id'] == 2){
            return "抱歉，您是企业用户不能成为员工";
        }
        $userupdate = $this->WeDb->update('user',"id={$userid}",$us_data);
        // 推送模板消息
        // $data = [
        //     'Template_id'=>'-FXFyfl80O9GKth78UKOrroqbBg1hPFxruvAAQ7rt2s',
        //     'openid'=>$user['open_id'],
        //     'url'=>'https://sy.zsicp.com/h5/#/pages/my/my',
        //     'title'=>'注销成功',
        //     'name'=>$user['username'],
        //     'mobile'=>date('Y-m-d'),
        //     'visittime'=>'消费者',
        //     'remark'=>'11111'
        // ];
        // $return = $this->Wechat_tool->sendMessage($data); 
        return ResultVo::success($userupdate);
    }
    // 员工列表接口
    public function my_user(){
        $userid = $this->uid;
        $find = $this->WeDb->find('user',"id={$userid}");
        $select = $this->WeDb->selectSQL('user',"where business_notice = {$find['business_notice']} and role_id = 3",'*');
        return ResultVo::success($select);
    }
    // 查询当前商家的商品
    public function my_menu(){
        $userid = $this->uid;
        $find = $this->WeDb->find('user',"id={$userid}");
        $businessid = $find['business_notice'];
        $select = $this->WeDb->selectSQL('menu',"where business_id = {$businessid} and if_delete = 0 ",'*');
        return ResultVo::success($select);
    }
    // 操作员注销接口
    public function my_quit(){
        $userid = $this->uid;
        $user = $this->WeDb->find('user',"id = {$userid}");
        if($user['role_id'] != 3){
            return ResultVo::error(ErrorCode::USER_NOT_BUSINESS['code'], ErrorCode::USER_NOT_BUSINESS['message']);
        }
        $qt_data = [
            'role_id' => 4,
            'business_notice' => '',
        ];
        $update = $this->WeDb->update('user',"id ={$userid}",$qt_data);
        // 推送模板消息
        $da_content = [
            'first'=>['value' => '注销成功', 'color' => "#000000"],
            'keyword1'=>['value' => $user['username'], 'color' => "#000000"],
            'keyword2'=>['value' => date('Y-m-d'), 'color' => "#000000"],
            'keyword3'=>['value' => '消费者', 'color' => "#000000"],
            'remark'=>['value' => '11111', 'color' => "#000000"],
        ];
        $data = [
            'Template_id'=>'-FXFyfl80O9GKth78UKOrroqbBg1hPFxruvAAQ7rt2s',
            'openid'=>$user['open_id'],
            'url'=>'https://sy.zsicp.com/h5/#/pages/my/my',
            'content'=>$da_content,
        ];
        $return = $this->Wechat_tool->sendMessage($data);
        return ResultVo::success($return); 
    }
     
}
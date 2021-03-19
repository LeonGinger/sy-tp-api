<?php

namespace app\wap\controller\business;

use app\wap\controller\Base;
use app\common\enums\ErrorCode;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;
use Workerman\Events\Select;
use think\db;
use app\model\Business;

/**
 * 商家相关
 */
class BusinessController extends Base
{

    private $table = 'Business';
    // 加入商家
    public function Apply_add()
    {
        $code = round_code(16);
        $appraisal_img = $this->request->param('appraisal_img');
        $appraisal = $this->WeDb->insertGetId('Business_appraisal',['appraisal_image' => $appraisal_img]);
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
        // var_dump($this->uid);
        // exit ;
        $ue_data = [
            'business_notice'=>$res,
            'role_id'=>2,
        ];
        $userupdate = $this->WeDb->update('User',"id={$this->uid}",$ue_data);
        return ResultVo::success($userupdate);
    }
    // 商家软删除
    public function Apply_delete()
    {
        $userid = $this->uid;
        $businessid = $this->request->param('business_id');
        $user = $this->WeDb->find('User',"id={$userid}");
        if($user['role_id'] != 2 && $user['role_id'] != 1){
            return "抱歉，您越权了";
        }
        $delete = $this->WeDb->update($this->table,"id={$businessid}",['delete_time'=>date('Y-m-d h:i:s')]);
        $roleupdate = $this->WeDb->update('User',"id={$userid}",['role_id'=>3]);
        return ResultVo::success($roleupdate);
    }
    // 商家查询接口all
    public function Apply_selectall()
    {
        $userid = $this->uid;
        $user = $this->WeDb->find('User',"id={$userid}");
        if($user['role_id'] != 2 && $user['role_id'] != 1){
            return "抱歉，您越权了";
        }
        $businessid = $user['business_notice'];
        $select = business::with('BusinessImg')->with('BusinessAppraisal')
                ->where("id={$businessid}")
                ->select();
        return ResultVo::success($select);
    }
    public function Apply_updateall(){
        $userid = $this->uid;
        $user = $this->WeDb->find('User',"id={$userid}");
        $business_name
        $responsile_name
        $responsible_phone
        $business_address
        $business_images
        $business_introduction
        $business_image
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
        $user = $this->WeDb->selectSQL('User',"where id={$userid} and delete_time is null","*");
        $business = $this->WeDb->find($this->table,"id={$user['business_notice']}");
        return ResultVo::success($business);
    }
    // 二维码授权操作员
    public function join_my(){
        $userid = $this->uid;
        $grant_code = $this->request->param('grant_code');
        $select = $this->WeDb->selectSQL($this->table,'where grant_code="'.$grant_code.'"','id');
        $selectid =  $select[0]['id'];
        $us_data = [
            'business_notice'=>(int)$selectid,        
            'role_id'=>3,
        ];
        $user = $this->WeDb->find('User',"id={$userid}");
        if($user['role'] == 2){
            return "抱歉，您是企业用户不能成为员工";
        }
        $userupdate = $this->WeDb->update('User',"id={$userid}",$us_data);
        return ResultVo::success($userupdate);
    }
    // 员工列表接口
    public function my_user(){
        $userid = $this->uid;
        $find = $this->WeDb->find('User',"id={$userid}");
        $select = $this->WeDb->selectSQL('User',"where business_notice = {$find['business_notice']} and role_id = 3",'*');
        return ResultVo::success($select);
    }
    // 查询当前商家的商品
    public function my_menu(){
        $userid = $this->uid;
        $find = $this->WeDb->find('User',"id={$userid}");
        $businessid = $find['business_notice'];
        $select = $this->WeDb->selectSQL('Menu',"where business_id = {$businessid} and if_delete = 0 ",'*');
        return ResultVo::success($select);
    }
     
}
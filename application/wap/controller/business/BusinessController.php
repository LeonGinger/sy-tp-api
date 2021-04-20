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
use think\facade\Config;
use app\model\Business;
use app\model\Business_notice;
use app\wap\controller\wechat\WechatController;
use app\model\Menu;

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
        $user = $this->WeDb->find('user', "id={$userid}");
        $time = date('Y-m-d H:i:s');
        if ($user['role_id'] == 2 || $user['role_id'] == 3) {
            return ResultVo::error(ErrorCode::USER_BUSINESS_TRUE['code'], ErrorCode::USER_BUSINESS_TRUE['message']);
        }
        $code = round_code(16);
        $business_name = $this->request->param('business_name');
        $business_address = $this->request->param('business_address');
        $responsible_name = $this->request->param('responsible_name');
        $responsible_phone = $this->request->param('responsible_phone');
        $appraisal_img = $this->request->param('appraisal_img');
        // var_dump($appraisal_img);
        // exit;
        if($business_name == '' || $business_address == '' || $responsible_name == '' || $responsible_phone == ''){
            return ResultVo::error(ErrorCode::DATA_NOT_CONTRNT['code'], ErrorCode::DATA_NOT_CONTRNT['message']);
        }
        $business = $this->WeDb->selectView('business','','business_name');
        for($i = 0;$i<count($business);$i++){
            if($business_name == $business[$i]['business_name'] && $business[$i]['delete_time'] != null){
                return ResultVo::error(ErrorCode::BUSINESS_REPEAT['code'], ErrorCode::BUSINESS_REPEAT['message']);
            }
        }
        $appraisal = $this->WeDb->insertGetId('business_appraisal', ['appraisal_image' => json_encode($appraisal_img)]);
        $re_data = array(
            'business_name' => $business_name,
            'business_address' => $business_address,
            'responsible_name' => $responsible_name,
            'responsible_phone' => $responsible_phone,
            'create_time' => date('Y-m-d H:i:s'),
            'state' => 2,
            'business_appraisal_id' => $appraisal,
            // 'business_introduction'=>$this->request->param('business_introduction'),
            'verify_if' => 3,
            'grant_code' => $code,
            'img_info' => 0,
        );
        $res = $this->WeDb->insertGetId($this->table, $re_data);
        $img_date = [
            'business_id' => $res,
            'business_image_injson' => '',
            'business_img_contentjson' => '',
        ];
        $insert = $this->WeDb->insertGetId('business_img',$img_date);
        // var_dump($this->uid);
        // exit ;
        $ue_data = [
            'business_notice' => $res,
            'role_id' => 4,
        ];
        $userupdate = $this->WeDb->update('user', "id={$this->uid}", $ue_data);
        $authroleadmin = $this->WeDb->update('auth_role_admin', "admin_id={$this->uid}", ['role_id'=>4]);
        // 模板消息
        // 推送给申请人↓
        $da_content = [
            'title' => ['value' => '您的申请已提交', 'color' => "#000000"],
            'business_name' => ['value' => $business_name, 'color' => "#000000"],
            'time' => ['value' => $time, 'color' => "#000000"],
            'remark' => ['value' => '待管理员核审，请稍后...', 'color' => "#000000"],
        ];
        $data = [
            'Template_id' => 'feLgG3FxLHR3F8WfH1vrrT1CcrnDWDjAJSm9Fv8FKU8',
            'openid' => $user['open_id'],
            'url' => 'https://sy.zsicp.com/h5/#/pages/my/my',
            'content' => $da_content,
        ];
        $return = $this->Wechat_tool->sendMessage($data);
        // * // 
        return ResultVo::success($userupdate);
    }
    // 商家软删除
    public function Apply_delete()
    {
        $userid = $this->uid;
        $businessid = $this->request->param('business_id');
        $business = $this->WeDb->find('business',"id = {$businessid}");
        $user = $this->WeDb->find('user', "id={$userid}");
        $business = $this->WeDb->find('business',"id = {$user['business_notice']}");
        if( $business['state'] != 1){
            return ResultVo::error(ErrorCode::STATE_NOT['code'], ErrorCode::STATE_NOT['message']);
        }
        if ($businessid != $user['business_notice']) {
            return ResultVo::error(ErrorCode::IS_NOT_BUSINESS['code'], ErrorCode::IS_NOT_BUSINESS['message']);
        }
        if ($user['role_id'] != 2 && $user['role_id'] != 1) {
            return ResultVo::error(ErrorCode::IS_NOT_BUSINESS['code'], ErrorCode::IS_NOT_BUSINESS['message']);
        }
        $delete = $this->WeDb->update($this->table, "id={$businessid}", ['delete_time' => date('Y-m-d H:i:s')]);
        // 推送给商家的所有人员↓
        $foruser = $this->WeDb->selectView('user',"business_notice = {$businessid}");
        for($i=0;$i<count($foruser);$i++){
            $da_content = [
                'business'=>['value' => $business['business_name'], 'color' => "#ff0000"],
            ];
            $data = [
                'Template_id'=>'kpULPN1XJZBpdCY1GBt3LQwsbRzLEwZUp9b0AHjBLj4',
                'openid'=>$foruser[$i]['open_id'],
                'url'=>'https://sy.zsicp.com/h5/#/my/my',
                'content'=>$da_content,
            ];
            $return = $this->Wechat_tool->sendMessage($data);
        }
        // * //
        $roleupdate = $this->WeDb->update('user',"business_notice={$businessid}", ['role_id' => 4, 'business_notice' => '']);
        $authroleadmin = $this->WeDb->update('auth_role_admin', "admin_id={$this->uid}", ['role_id'=>4]);
        return ResultVo::success($roleupdate);
    }
    // 商家查询接口all
    public function Apply_selectall()
    {
        $userid = $this->uid;
        $user = $this->WeDb->find('user', "id={$userid}");
        $business = $this->WeDb->find('business',"id = {$user['business_notice']}");
        if( $business['state'] != 1){
            return ResultVo::error(ErrorCode::STATE_NOT['code'], ErrorCode::STATE_NOT['message']);
        }
        if ($user['role_id'] != 1 && $user['role_id'] != 2) {
            return ResultVo::error(ErrorCode::USER_NOT_LIMIT['code'], ErrorCode::USER_NOT_LIMIT['message']);
        }
        $businessid = $user['business_notice'];
        $select = business::with(['BusinessAppraisal','BusinessImg'])
            // ->with('BusinessImg')
            // ->join('business_img', 'business_img.business_id = business.id')
            ->where("id={$businessid} and delete_time is null")
            ->select();
        return ResultVo::success($select);
    }
    // 修改所有信息接口(会修改状态为未审核)
    public function Apply_updateall()
    {
        $userid = $this->uid;
        $user = $this->WeDb->find('user', "id={$userid}");
        $business = $this->WeDb->find('business',"id = {$user['business_notice']}");
        if( $business['state'] != 1){
            return ResultVo::error(ErrorCode::STATE_NOT['code'], ErrorCode::STATE_NOT['message']);
        }
        $business_id = $user['business_notice'];
        // $img_key = $this->request->param('img_key');
        // $business_img_id_json  = $this->request->param('$business_img_id_json');
        $business_appraisal = $this->WeDb->find('business_appraisal',"id = {$business['business_appraisal_id']}");
        $log_data = [
            'business_name'=>$business['business_name'],
            'responsible_name'=>$business['responsible_name'],            
            'responsible_phone'=>$business['responsible_phone'],            
            'business_address'=>$business['business_address'],            
            'appraisal_image'=>$business_appraisal['appraisal_image'],
            'business_id'=>$business['id'],
        ];
        //////////////////////////////////////////////////////////////
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
            'business_name' => $business_name,
            'responsible_name' => $responsible_name,
            'responsible_phone' => $responsible_phone,
            'business_address' => $business_address,
            'business_images' => json_encode($business_images),
            'business_introduction' => $business_introduction,
            'verify_if' => 3,
            'state'=>1,
        ];
        $update = $this->WeDb->update($this->table, "id = {$business_id}", $data);
        $update2 = false;
        if($business_images_injson != null){
            $img_data = [
                'business_image_injson' => json_encode($business_images_injson),
                'business_img_contentjson' => json_encode($business_img_contentjson,JSON_UNESCAPED_UNICODE),
            ];
            $update2 = $this->WeDb->update('business_img', "business_id = {$business_id}", $img_data);
        }
        $ap_data = [
            'appraisal_image' => json_encode($appraisal_image)
        ];
        $business = $this->WeDb->find($this->table, "id = {$business_id}");
        $update3 = $this->WeDb->update('business_appraisal', "id = {$business['business_appraisal_id']}", $ap_data);
        $data = [
            'update1' => $update,
            'update2' => $update2,
            'update3' => $update3,
        ];
        return ResultVo::success($data);
    }
    // 修改部分信息接口(不会修改状态为未审核)
    public function Apply_update()
    {
        $userid = $this->uid;
        $user = $this->WeDb->find('user', "id={$userid}");
        $business = $this->WeDb->find('business',"id = {$user['business_notice']}");
        if( $business['state'] != 1){
            return ResultVo::error(ErrorCode::STATE_NOT['code'], ErrorCode::STATE_NOT['message']);
        }
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
        if($business_images == null || $business_introduction == null || 
        $business_images_injson == null){
            return ResultVo::error(ErrorCode::DATA_NOT_CONTRNT['code'], ErrorCode::DATA_NOT_CONTRNT['message']);
        }
        // $appraisal_image = $this->request->param('appraisal_image'); 
        $data = [
            // 'business_name'=>$business_name,
            // 'responsible_name'=>$responsible_name,
            // 'responsible_phone'=>$responsible_phone,
            // 'business_address'=>$business_address,
            'business_images' => json_encode($business_images),
            'business_introduction' => $business_introduction,
            // 'verify_if'=>3,
        ];
        $update = $this->WeDb->update($this->table, "id = {$business_id}", $data);
        $img_data = [
            'business_image_injson' => json_encode($business_images_injson),
            'business_img_contentjson' => json_encode($business_img_contentjson,JSON_UNESCAPED_UNICODE),
        ];
        $update2 = $this->WeDb->update('business_img', "business_id = {$business_id}", $img_data);
        // $ap_data = [
        //     'appraisal_image'=>$appraisal_image
        // ];
        // $business = $this->WeDb->find($this->table,"id = {$business_id}");
        // $update3 = $this->WeDb->update('business_appraisal',"id = {$business['business_appraisal_id']}",$ap_data);
        $data = [
            'update1' => $update,
            'update2' => $update2,
            // 'update3'=>$update3,
        ];
        return ResultVo::success($data);
    }
    // 移除员工
    public function out_my_user()
    {
        $userid = $this->uid;
        $out_id = $this->request->param('');
        $user = $this->WeDb->find('user', "id={$userid}");
        $business = $this->WeDb->find('business',"id = {$user['business_notice']}");
        if( $business['state'] != 1){
            return ResultVo::error(ErrorCode::STATE_NOT['code'], ErrorCode::STATE_NOT['message']);
        }
        $out_user = $this->WeDb->find('user', "id = {$out_id}");
        $business = $this->WeDb->find('business', "id = {$user['business_notice']}");
        $time = date('Y-m-d H:i:s');
        if ($user['role_id'] != 2 && $user['role_id'] != 1) {
            return ResultVo::error(ErrorCode::IS_NOT_BUSINESS['code'], ErrorCode::IS_NOT_BUSINESS['message']);
        }
        $out_data = [
            'role_id' => 4,
            'business_notice' => ''
        ];
        $update = $this->WeDb->update('user', "id = {$out_id}", $out_data);
        $authroleadmin = $this->WeDb->update('auth_role_admin', "admin_id={$this->uid}", ['role_id'=>4]);
        // 推送模板消息
        // 推送给操作员↓
        $da_content = [
            'content1' => ['value' => '您已被移出公司', 'color' => "#000000"],
            'content2' => ['value' => "公司名称：{$business['business_name']}", 'color' => "#000000"],
            'content3' => ['value' => "移出时间：{$time}", 'color' => "#000000"],
            'content4' => ['value' => '你的账号已更改为消费者', 'color' => "#000000"],
            'remark' => ['value' => '感谢您对公司作出的贡献', 'color' => "#000000"],
        ];
        $data = [
            'Template_id' => 'ctssIEGGg1132D-Xt8t0CJ1d4RWCLtKj5iO8lcAzeP4',
            'openid' => $out_user['open_id'],
            'url' => 'https://sy.zsicp.com/h5/#/pages/my/my',
            'content' => $da_content,
        ];
        $return = $this->Wechat_tool->sendMessage($data);
        // 推送给商家↓
        $bs_content = [
            'content1' => ['value' => "{$out_user['username']} 已被你移出公司", 'color' => "#000000"],
            'content2' => ['value' => "公司名称：{$business['business_name']}", 'color' => "#000000"],
            'content3' => ['value' => "移出时间：{$time}", 'color' => "#000000"],
            'content4' => ['value' => "{$out_user['username']}的账号已进行变更", 'color' => "#000000"],
            'remark' => ['value' => '操作成功', 'color' => "#000000"],
        ];
        $data = [
            'Template_id' => 'ctssIEGGg1132D-Xt8t0CJ1d4RWCLtKj5iO8lcAzeP4',
            'openid' => $user['open_id'],
            'url' => 'https://sy.zsicp.com/h5/#/pages/employee/employee-list',
            'content' => $bs_content,
        ];
        $return = $this->Wechat_tool->sendMessage($data);
        //*//
        return ResultVo::success($update);
    }
    /**
     * 证书上传
     */
    public function Upload()
    {
    }
    // 根据token查询当前用户的商家信息
    public function info()
    {
        $userid = $this->uid;
        $user = $this->WeDb->selectSQL('user', "where id={$userid} and delete_time is null", "*");
        $business = $this->WeDb->find($this->table, "id={$user['business_notice']}");
        return ResultVo::success($business);
    }
    // 二维码授权操作员
    public function join_my()
    {
        $userid = $this->uid;
        $user = $this->WeDb->find('user', "id={$userid}");   
        $grant_code = $this->request->param('grant_code');
        $business = $this->WeDb->find($this->table, 'grant_code="' . $grant_code . '"');
        if( $business['state'] != 1){
            return ResultVo::error(ErrorCode::STATE_NOT['code'], ErrorCode::STATE_NOT['message']);
        }
        if($user['role_id'] == 3 || $user['role_id'] ==2){
            return ResultVo::error(ErrorCode::ROLE_NOT_THIS['code'],ErrorCode::ROLE_NOT_THIS['message']);
        }
        $businessid =  $business['id'];
        $business_user = $this->WeDb->find('user', "business_notice = {$businessid} and role_id = 2");
        $business_mane = $this->WeDb->selectView('user', "business_notice = {$businessid}");
        $business_number = count($business_mane) - 1;
        $time = date('Y-m-d H:i:s');
        $us_data = [
            'business_notice' => (int)$businessid,
            'role_id' => 3,
        ];
        $user = $this->WeDb->find('user', "id={$userid}");
        if ($user['role_id'] == 2) {
            return ResultVo::error(ErrorCode::USER_ROLE_IN['code'], ErrorCode::USER_ROLE_IN['message']);
        }else if ($user['role_id'] == 3) {
            return ResultVo::error(ErrorCode::USER_ROLE_REPEAT['code'], ErrorCode::USER_ROLE_REPEAT['message']);
        }
        $userupdate = $this->WeDb->update('user', "id={$userid}", $us_data);
        $authroleadmin = $this->WeDb->update('auth_role_admin', "admin_id={$this->uid}", ['role_id'=>$us_data['role_id']]);
        // 推送模板消息
        // 推送给操作员↓
        $da_content = [
            'business_name' => ['value' => $business['business_name'], 'color' => "#000000"],
            'business_user' => ['value' => $business['responsible_name'], 'color' => "#000000"],
            'business_phone' => ['value' => $business['responsible_phone'], 'color' => "#000000"],
            'business_address' => ['value' => $business['business_address'], 'color' => "#000000"],
            'remark' => ['value' => '恭喜您加入到本公司', 'color' => "#000000"],
        ];
        $data = [
            'Template_id' => '-hpsvO5xcmL1l2Af_6_VFOLO65vRMRPggsOKnpejQo30',
            'openid' => $user['open_id'],
            'url' => 'https://sy.zsicp.com/h5/#/pages/my/my',
            'content' => $da_content,
        ];
        $return = $this->Wechat_tool->sendMessage($data);
        // 推送给商家↓
        $bs_content = [
            'username' => ['value' => $user['username'], 'color' => "#000000"],
            'phone' => ['value' => $user['phone'], 'color' => "#000000"],
            'time' => ['value' => $time, 'color' => "#000000"],
            'number' => ['value' => $business_number, 'color' => "#000000"],
        ];
        $data = [
            'Template_id' => '-Ga5jQnQmi12lHPsSxfZk1S_PgMiu93xzmDZa9589WY',
            'openid' => $business_user['open_id'],
            'url' => 'https://sy.zsicp.com/h5/#/pages/employee/employee-list',
            'content' => $bs_content,
        ];
        $return = $this->Wechat_tool->sendMessage($data);
        //*//
        return ResultVo::success($userupdate);
    }
    // 员工列表接口
    public function my_user()
    {
        $userid = $this->uid;
        $user = $this->WeDb->find('user', "id={$userid}");
        $business = $this->WeDb->find('business',"id = {$user['business_notice']}");
        if( $business['state'] != 1){
            return ResultVo::error(ErrorCode::STATE_NOT['code'], ErrorCode::STATE_NOT['message']);
        }
        $role = $user['role_id'];
        if($role != 1 && $role != 2 && $role != 3){
            return ResultVo::error(ErrorCode::USER_NOT_LIMIT['code'], ErrorCode::USER_NOT_LIMIT['message']);
        }
        $select = $this->WeDb->selectSQL('user', "where business_notice = {$user['business_notice']} and role_id != 2", '*');
        return ResultVo::success($select);
    }
    // 查询当前商家的商品
    public function my_menu()
    {
        $userid = $this->uid;
        $user = $this->WeDb->find('user', "id={$userid}");
        $business = $this->WeDb->find('business',"id = {$user['business_notice']}");
        if( $business['state'] != 1){
            return ResultVo::error(ErrorCode::STATE_NOT['code'], ErrorCode::STATE_NOT['message']);
        }
        $businessid = $user['business_notice']; 
        $role = $user['role_id'];
        if($role != 1 && $role != 2 && $role != 3){
            return ResultVo::error(ErrorCode::USER_NOT_LIMIT['code'], ErrorCode::USER_NOT_LIMIT['message']);
        }
        $select = Menu::with(['MenuMonitor','MenuCertificate'])
                ->where("business_id = {$businessid} and if_delete != 1")
                ->select();
        return ResultVo::success($select);
    }
    // 操作员注销接口
    public function my_quit()
    {
        $userid = $this->uid;
        $user = $this->WeDb->find('user', "id={$userid}");
        $business = $this->WeDb->find('business',"id = {$user['business_notice']}");
        if( $business['state'] != 1){
            return ResultVo::error(ErrorCode::STATE_NOT['code'], ErrorCode::STATE_NOT['message']);
        }
        $business_user = $this->WeDb->find('user', "business_notice = {$user['business_notice']} and role_id = 2");
        $business = $this->WeDb->find('business', "id = {$business_user['business_notice']}");
        if ($user['role_id'] != 3) {
            return ResultVo::error(ErrorCode::USER_NOT_BUSINESS['code'], ErrorCode::USER_NOT_BUSINESS['message']);
        }
        $qt_data = [
            'role_id' => 4,
            'business_notice' => '',
        ];
        $update = $this->WeDb->update('user', "id ={$userid}", $qt_data);
        $authroleadmin = $this->WeDb->update('auth_role_admin', "admin_id={$userid}", ['role_id'=>$qt_data['role_id']]);
        // 推送模板消息
        // 推送给操作员↓
        $da_content = [
            'first' => ['value' => '注销成功', 'color' => "#000000"],
            'keyword1' => ['value' => $user['username'], 'color' => "#000000"],
            'keyword2' => ['value' => date('Y-m-d'), 'color' => "#000000"],
            'keyword3' => ['value' => '消费者', 'color' => "#000000"],
            'remark' => ['value' => '感谢您对公司作出的贡献', 'color' => "#000000"],
        ];
        $data = [
            'Template_id' => '-5aNPVXsZQ90c0gEK90-uT0gEb9nezIZeel3RLA8pGuo',
            'openid' => $user['open_id'],
            'url' => 'https://sy.zsicp.com/h5/#/pages/my/my',
            'content' => $da_content,
        ];
        $return = $this->Wechat_tool->sendMessage($data);
        // 推送给商家↓
        $bs_content = [
            'out_name' => ['value' => $user['username'], 'color' => "#000000"],
            'business_name' => ['value' => $business['business_name'], 'color' => "#000000"],
            'out_data' => ['value' => date('Y-m-d'), 'color' => "#000000"],
        ];
        $data = [
            'Template_id' => '1teYKoeSKUdNGtMOGUNg0cIcXDN5qXU43f3nVJx9bvc',
            'openid' => $business_user['open_id'],
            'url' => 'https://sy.zsicp.com/h5/#/pages/employee/employee-list',
            'content' => $bs_content,
        ];
        $return = $this->Wechat_tool->sendMessage($data);
        //*//
        return ResultVo::success($update);
    }
    public function businessfind(){
		$business_id = $this->request->param('business_id');
		$business = Business::with(['BusinessAppraisal','BusinessImg'])
					->where("id = {$business_id}")
					->find();
        $menu = Menu::with(['CertificateMenu','MenuMonitor'])
                    ->where("business_id = {$business_id}")
                    ->select();
        $business['menuAll'] = $menu;
        return ResultVo::success($business);
	}
}

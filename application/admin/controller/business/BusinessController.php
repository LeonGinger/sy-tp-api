<?php

namespace app\admin\controller\business;

use app\admin\controller\BaseCheckUser;
use app\admin\controller\Base;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use think\facade\Config;
use app\model\Business;
use think\Queue;
use redis\Redis;

use function GuzzleHttp\json_encode;

/**
 * 商家
 */
class BusinessController extends BaseCheckUser
{
	private $tables = 'business';
    /**
     * 商家列表
     * @param
     *        type 3-已提交(待审核)
     *        name 商家名称
     */
    public function index(){

        // $type = $this->request->param('type');
        // $name = $this->request->param('name');
        // $verify_if = $this->request->param('verify_if');
        $data = $this->request->param('');
        $where = '';
        $search[0] = !empty($data['state'])?'state = '.$data['state']:'';
        $search[1] = !empty($data['name'])?'business_name like "%'.$data['name'].'%"':'';
        $search[2] = !empty($data['verify_if'])?'verify_if = '.$data['verify_if']:'';
		$search[3] = 'delete_time is null';
		

        foreach ($search as $key => $value) {
            # code...
            if($value){
                $where=$where.$value.' and ';
            }
        }
        $where=substr($where,0,strlen($where)-5);

        $list = Business::with([
            'BusinessAppraisal',
            'BusinessImg',
            'BossUser'=>function($query){
            	$boss_where = @$data['type']=='apply'?"role_id = 4":"role_id = 2";
                $query->where($boss_where)->find();
        }])->where($where)->page($data['page'],$data['size'])->order('verify_if desc,create_time desc')->select()->toarray();
        //$list = $this->WeDb->selectView($this->tables,$where);
        $total =  $this->WeDb->totalView($this->tables,$where);
        return ResultVo::success(['list'=>$list,'total'=>$total]);
    }
    /*添加*/
    public function save(){}
    
    /*编辑*/
    public function edit(){
    	$data = $this->request->post();
    	$business_info = $this->WeDb->find($this->tables,'id = '.$data['id']);
    	// $up_data = array(
    	// 	'business_name' => $data['business_name'], 
    	// 	'responsible_name' => $data['responsible_name'],
    	// 	'responsible_phone' => $data['responsible_phone'],
    	// );
    	$result = $this->WeDb->update($this->tables,'id = '.$data['id'],$data);
		return ResultVo::success();
    }
    /*改变状态*/
    public function state(){
    	$data = $this->request->post();
		// var_dump($data);
		// exit;
    	if(@$data['state']){
    		$result = $this->WeDb->update($this->tables,'id = '.$data['id'],['state'=>$data['state']]);
    	}
    	if(@$data['verify_if']){
    		$result_verif = self::verify_if($data);
    		// $result = $this->WeDb->update($this->tables,'id = '.$data['id'],['verify_if'=>$data['verify_if'],'state'=>$result_verif]);
			if($result_verif == 12138){
				return ResultVo::error(12138,"此商家已被用户解除绑定，系统将自动删除");
			}
    	}
    	try{
    		return ResultVo::success($result_verif);
    	} catch (\Throwable $th) {
    		return ResultVo::success();
    	}
		
    }

    /*审核*/
    private function verify_if($data){
		// var_dump($data);
		// exit;
    	$business_info = $this->WeDb->find($this->tables,'id = '.$data['id']);
    	$business_user = $this->WeDb->find('user','business_notice = '.$data['id']);
		// var_dump($business_user);
		// exit;
		if(isset($business_user)){}else{
			$delete = $this->WeDb->update('business',"id = {$business_info['id']}",['delete_time'=>date('Y-m-d H:i:s')]);
			return 12138;
		}
		
		// var_dump($business_user);
		// exit;
    	switch ($data['verify_if']) {
    		case '1':
    			//通过
    			# code...
    			$send_data = array(
    				'url'=>'',
    				'row'=>array(
    					'first'=>'',
    					'keyword1'=>'',
    					//keyword...
    					'remark'=>'',
    				),

    			);
    			// $send_result = $this->Wechat_tool->send_msg($business_user['open_id'],$tpl_id,$send_data);
				// var_dump($data['id']);
				// exit;
				$user = $this->WeDb->update('user',"id = {$business_user['id']}",['role_id'=>2]);
                $authroleadmin = $this->WeDb->update('auth_role_admin', "admin_id={$business_user['id']}", ['role_id'=>2]);
				$business = $this->WeDb->update('business',"id = {$business_user['business_notice']}",['verify_if'=>1,'state'=>1]);
				// 推送给申请人↓
				$da_content = [
					'first' => ['value' => '您的企业入驻申请已通过', 'color' => "#000000"],
					'keyword1' => ['value' => $business_user['username'], 'color' => "#000000"],
					'keyword2' => ['value' => $business_info['business_name'], 'color' => "#000000"],
					'keyword3' => ['value' => "您的申请已通过管理员的审核，快进入系统使用把！", 'color' => "#000000"],
					'remark' => ['value' => '溯源系统', 'color' => "#000000"],
				  ];
				  $data = [
					'Template_id' => 'MbHfsg51fQ1Zzty6F8-9lExm_Cb4ClinviJRR9TgOms',
					'openid' => $business_user['open_id'],
					'url' => Config::get('domain_h5').'#/pages/my/my',
					'content' => $da_content,
				  ];
				  $return = $this->Wechat_tool->sendMessage($data);
				  // * //
                $state = 1;
    			break;
    		case '2':
    			//不通过
    			# code...
    			$send_data = array(
    				'url'=>'',
    				'row'=>array(
    					'first'=>'',
    					'keyword1'=>'',
    					//keyword...
    					'remark'=>'',
    				),
    			);
    			//$send_result = $this->Wechat_tool->send_msg($business_user['open_id'],$tpl_id,$send_data);
				// $user = $this->WeDb->update('user',"id = {$business_user['id']}",['role_id'=>2]);
                // $authroleadmin = $this->WeDb->update('auth_role_admin', "admin_id={$business_user['id']}", ['role_id'=>2]);
				if($business_user['role_id']==2){//判别是否是修改，角色为2是修改，角色为4是加入
					// 恢复历史信息
					$log_business = $this->WeDb->selectView('business_update_log',"business_id = {$business_user['business_notice']}",'*','update_time asc');
					$log_business = $log_business[0];
					$lg_data1=[
						'business_name'=>$log_business['business_name'],
						'responsible_name'=>$log_business['responsible_name'],
						'responsible_phone'=>$log_business['responsible_phone'],
						'business_address'=>$log_business['business_address'],
					];
					$lg_data2=['appraisal_image'=>$log_business['appraisal_image']];
					$business_log_update = $this->WeDb->update('business',"id = {$business_user['business_notice']}",$lg_data1);
					$business_appraisal_update = $this->WeDb->update('business_appraisal',"id = {$business_info['business_appraisal_id']}",$lg_data2);
					$business = $this->WeDb->update('business',"id = {$business_user['business_notice']}",['verify_if'=>1,'state'=>1]);
					// 推送给申请人↓
					$da_content = [
						'first' => ['value' => '您修改的信息已被管理员驳回', 'color' => "#000000"],
						'keyword1' => ['value' => $business_user['username'], 'color' => "#000000"],
						'keyword2' => ['value' => $log_business['business_name'], 'color' => "#000000"],
						'keyword3' => ['value' => "您的申请未通过管理员审核,已恢复成修改前的信息", 'color' => "#000000"],
						'remark' => ['value' => "原由为:{$data['content']}", 'color' => "#000000"],
					];
					$data = [
						'Template_id' => 'MbHfsg51fQ1Zzty6F8-9lExm_Cb4ClinviJRR9TgOms',
						'openid' => $business_user['open_id'],
						'url' => Config::get('domain_h5').'#/pages/my/my',
						'content' => $da_content,
					];
					$return = $this->Wechat_tool->sendMessage($data);
					// * //
				}else{
					$business = $this->WeDb->update('business',"id = {$business_user['business_notice']}",['verify_if'=>2,'state'=>2]);
					// 推送给申请人↓
					$da_content = [
						'first' => ['value' => '您的入驻信息信息已被管理员驳回', 'color' => "#000000"],
						'keyword1' => ['value' => $business_user['username'], 'color' => "#000000"],
						'keyword2' => ['value' => $business_info['business_name'], 'color' => "#000000"],
						'keyword3' => ['value' => "您的申请未通过管理员审核", 'color' => "#000000"],
						'remark' => ['value' => "原由为:{$data['content']}", 'color' => "#000000"],
					];
					$data = [
						'Template_id' => 'MbHfsg51fQ1Zzty6F8-9lExm_Cb4ClinviJRR9TgOms',
						'openid' => $business_user['open_id'],
						'url' => Config::get('domain_h5').'#/pages/my/my',
						'content' => $da_content,
					];
					$return = $this->Wechat_tool->sendMessage($data);
					// * //
				}
                $state = 2;
    			break;
    		default:
    			# code...
    			break;
    	}
    	return $state;
    }
	// 商家信息页
	public function businessAll(){
		$data = $this->request->param('');
        $id = $data['business_id']?:$this->adminInfo['business_notice'];

		$business = Business::where("id = {$id}")->find();
		// var_dump($business);
		// exit;
		$business['business_images'] = json_decode($business['business_images']);
		$business_appraisal = $this->WeDb->find('business_appraisal',"id = {$business['business_appraisal_id']}");
		$business_img = $this->WeDb->find('business_img',"business_id = {$business['id']}");
		$business_appraisal['appraisal_image'] = json_decode($business_appraisal['appraisal_image']);
		$business_img['business_image_injson'] = json_decode($business_img['business_image_injson']);
		$business_img['business_img_contentjson'] = json_decode($business_img['business_img_contentjson']); 
		if($business_appraisal){
			$business['business_appraisal'] = $business_appraisal;
		}
		if($business_img){
			$business['business_img'] = $business_img;
		}
		return ResultVo::success($business);
	}
	// 商家信息更新
	public function business_update(){
		$data = $this->request->param('');
		// var_dump($data);
		// exit;
		$business = json_decode($data['business'],true);
		$log_business = $this->WeDb->find('business',"id = {$business['id']}");
		$log_appraisal = $this->WeDb->find('business_appraisal',"id = {$business['business_appraisal_id']}");
		if($business['business_name'] != $log_business['business_name'] || $business['responsible_name'] != $log_business['responsible_name'] || 
		   $business['responsible_phone'] != $log_business['responsible_phone'] || $business['business_address'] != $log_business['business_address'] ||
		   json_encode($business['business_appraisal']['appraisal_image']) != $log_appraisal['appraisal_image']){
			//    var_dump($business['business_name']);
			//    var_dump($log_business['business_name']);
			//    var_dump($business['responsible_name']);
			//    var_dump($log_business['responsible_name']);
			//    var_dump($business['responsible_phone']);
			//    var_dump($log_business['responsible_phone']);
			//    var_dump($business['business_address']);
			//    var_dump($log_business['business_address']);
			//    var_dump($business['business_appraisal']['appraisal_image']);
			//    var_dump($log_appraisal['appraisal_image']);
				// 更改未核审状态并存历史记录
				$state = $this->WeDb->update('business',"id = {$business['id']}",['verify_if'=>3]);
				$log_data = [
					'business_name'=>$log_business['business_name'],
					'responsible_name'=>$log_business['responsible_name'],            
					'responsible_phone'=>$log_business['responsible_phone'],            
					'business_address'=>$log_business['business_address'],            
					'appraisal_image'=>$log_appraisal['appraisal_image'],
					'business_id'=>$log_business['id'],
					'update_time'=>date('Y-m-d H:i:s'),
				];
				$insert = $this->WeDb->insert('business_update_log',$log_data);
		   }
		$data_a=[
			'business_name'=>$business['business_name'],
			'responsible_name'=>$business['responsible_name'],
			'responsible_phone'=>$business['responsible_phone'],
			'business_address'=>$business['business_address'],
			'business_introduction'=>$business['business_introduction'],
			'business_images'=>json_encode($business['business_images'])
		];
		$data_b=[
			'appraisal_image'=>json_encode($business['business_appraisal']['appraisal_image'])
		];
		$data_c=[
			'business_image_injson'=>json_encode($business['business_img']['business_image_injson']),
			'business_img_contentjson'=>json_encode($business['business_img']['business_img_contentjson']),
		];
		$user = $this->WeDb->find('user',"id = {$this->adminInfo['id']}");
		$my_business = $this->WeDb->find('business',"id = {$user['business_notice']}");
		$update1 = $this->WeDb->update('business',"id = {$my_business['id']}",$data_a);
		$update2 = $this->WeDb->update('business_appraisal',"id = {$my_business['business_appraisal_id']}",$data_b);
		$update3 = $this->WeDb->update('business_img',"business_id = {$my_business['id']}",$data_c);
		$key = 0;
		$Max_data=[
			'inster1' => $update1,
			'inster2' => $update2,
			'inster3' => $update3,
			'key' => $key,
		];
		return ResultVo::success($Max_data);
	}
}	
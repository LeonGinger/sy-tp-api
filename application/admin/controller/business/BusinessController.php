<?php

namespace app\admin\controller\business;

use app\admin\controller\BaseCheckUser;
use app\admin\controller\Base;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use app\model\Business;

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
                $query->where("role_id = 2")->find();
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
    	if(@$data['state']){
    		$result = $this->WeDb->update($this->tables,'id = '.$data['id'],['state'=>$data['state']]);
    	}
    	if(@$data['verify_if']){
    		$result_verif = $this->verify_if($data);
    		// $result = $this->WeDb->update($this->tables,'id = '.$data['id'],['verify_if'=>$data['verify_if'],'state'=>$result_verif]);
    	}
		return ResultVo::success();
    }

    /*审核*/
    private function verify_if($data){
		// var_dump($data);
		// exit;
    	$business_info = $this->WeDb->find($this->tables,'id = '.$data['id']);
    	$business_user = $this->WeDb->find('user','business_notice = '.$data['id']);
		
		// var_dump($business_user);
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
					$log_business = $this->WeDb->selectView('business_update_log',"business_id = {$business_user['business_notice']}",'*','update_time desc');
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
				}else{
					$business = $this->WeDb->update('business',"id = {$business_user['business_notice']}",['verify_if'=>2,'state'=>2]);
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
        $id = $data['business_id']?:$this->adminInfo['id'];

		$business = Business::where("id = {$id}")
		->find();
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
		   $business['business_appraisal']['appraisal_image'] != $log_appraisal['appraisal_image']){
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
		$user = $this->WeDb->find('user',"id = {$data['ADMIN_ID']}");
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
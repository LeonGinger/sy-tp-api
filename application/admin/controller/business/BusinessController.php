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
        }])->where($where)->page($data['page'],$data['size'])->select()->toarray();
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
    		$result = $this->WeDb->update($this->tables,'id = '.$data['id'],['verify_if'=>$data['verify_if'],'state'=>$result_verif]);
    	}
		return ResultVo::success();
    }

    /*审核*/
    private function verify_if($data){
		// var_dump($data);
		// exit;
    	$business_info = $this->WeDb->find($this->tables,'id = '.$data['id']);
    	$business_user = $this->WeDb->find('user','business_notice = '.$data['id']);
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
				$user = $this->WeDb->update('user',"id = {$business_user['id']}",['role_id'=>2]);
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
				$user = $this->WeDb->update('user',"id = {$business_user['id']}",['role_id'=>2]);
				$business = $this->WeDb->update('business',"id = {$business_user['business_notice']}",['verify_if'=>2,'state'=>2]);
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
		$userid = $data['ADMIN_ID'];
		$user = $this->WeDb->find('user',"id = {$userid}");
		$business = Business::where("id = {$user['business_notice']}")
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
		$business = json_decode($data['business'],true);
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
		if($update2 != "无需要更新的值" || $business['business_name']!=$my_business['business_name'] ||$business['responsible_name']!=$my_business['responsible_name'] ||$business['responsible_phone']!=$my_business['responsible_phone'] ||$business['business_address']!=$my_business['business_address']){
				// var_dump($business['business_address'],$my_business['business_address']);
				// exit;
				$key = $this->WeDb->update('business',"id = {$my_business['id']}",['verify_if'=>3]);
			}
		$Max_data=[
			'inster1' => $update1,
			'inster2' => $update2,
			'inster3' => $update3,
			'key' => $key,
		];
		return ResultVo::success($Max_data);
	}
}	
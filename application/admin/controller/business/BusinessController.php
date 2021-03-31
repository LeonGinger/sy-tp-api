<?php

namespace app\admin\controller\business;

use app\admin\controller\BaseCheckUser;
use app\admin\controller\Base;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use app\model\Business;

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
    	$business_info = $this->WeDb->find($this->tables,'id = '.$data['id']);
    	$business_user = $this->WeDb->find('user','role_id = 2 and business_notice = '.$data['id']);
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
    			//$send_result = $this->Wechat_tool->send_msg($business_user['open_id'],$tpl_id,$send_data);
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
                $state = 2;
    			break;
    		
    		default:
    			# code...
    			break;
    	}
    	return $state;
    }
}	
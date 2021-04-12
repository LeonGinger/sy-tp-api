<?php

namespace app\admin\controller\business;

use app\admin\controller\BaseCheckUser;
use app\admin\controller\Base;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use app\model\Menu;
/**
 * 商品管理
 */
class MenuController extends BaseCheckUser
{
	private $tables = 'menu';
	/**
	 * 商品列表
	 * @DateTime     2021-04-02T17:04:04+0800
	 * @LastTime     2021-04-02T17:04:04+0800
	 * @return       [type]                        [description]
	 */
	public function index(){
		$data = $this->request->param('');
		// var_dump($data);
		// exit;
        $order = isset($data['order'])?$data['order']:'create_time desc';
        $user = $this->WeDb->find('user',"id = {$data['ADMIN_ID']}");
        $where = '';
        $search[0] = !empty($data['state'])?'state = '.$data['state']:'';
        $search[1] = 'if_delete = 0 ';
        $search[2] = "business_id = {$user['business_notice']}";
        // $search[1] = !empty($data['name'])?'business_name like "%'.$data['name'].'%"':'';
        // $search[2] = !empty($data['verify_if'])?'verify_if = '.$data['verify_if']:'';
        
        foreach ($search as $key => $value) {
            # code...
            if($value){
                $where=$where.$value.' and ';
            }
        }
        $where=substr($where,0,strlen($where)-5);
       	//hasBusiness
        $list = Menu::with(['RecommendMenu','MonitorMenu','CertificateMenu'])
        				->where($where)
        				->page($data['page'],$data['size'])
                        ->order($order)
        				->select()
        				->toarray();
        //$list = $this->WeDb->selectView($this->tables,$where);
        $total =  $this->WeDb->totalView($this->tables,$where);
        for($i=0 ; $i<count($list);$i++){
            $business = $this->WeDb->find('business',"id = {$list[$i]['business_id']}");
            $list[$i]['business_name'] = $business['business_name'];
            $list[$i]['menu_images_json'] = json_decode($list[$i]['menu_images_json']);
        }
        return ResultVo::success(['list'=>$list,'total'=>$total]);
	}

	/**
	 * 商品详情
	 * @Param
	 * @DateTime     2021-04-02T17:03:50+0800
	 * @LastTime     2021-04-02T17:03:50+0800
	 * @return       [type]                        [description]
	 */
	public function details(){
		$data = $this->request->param('');
		$where = 'id = '.$data['id'];
		$details = Menu::with(['RecommendMenu','MonitorMenu','CertificateMenu','hasBusiness'])
        				->where($where)
        				->find();
        if(!$details){return ResultVo::error("商品不存在");}
        //商品图片
        if(isset($details['menu_images_json'])){$details['menu_images_json'] = json_decode($details['menu_images_json']);}
        //证书图片
        if(isset($details['certificate_menu']['certificate_image'])){$details['certificate_menu']['certificate_image'] = json_decode($details['certificate_menu']['certificate_image']);}
        //推荐商品图片
        if(isset($details['recommend_menu'])){$details['recommend_menu'] = json_decode($details['recommend_menu']);}
        //检验报告
        if(isset($details['monitor_menu']['monitor_image'])){$details['monitor_menu']['monitor_image'] = json_decode($details['monitor_menu']['monitor_image']);}
        return ResultVo::success($details);
	}
    /**
     * 添加商品
     * @HtttpRequest                               get|post
     * @Author       GNLEON
     * @Param
     * @DateTime     2021-04-07T16:47:47+0800
     * @LastTime     2021-04-07T16:47:47+0800
     */
    public function add(){
        $data = $this->request->param('');
        // var_dump($data['business_id']);
        // exit;
        $user = $this->WeDb->find('user',"id = {$data['ADMIN_ID']}");
        $in_data = array(
            'menu_name'=>isset($data['menu_name'])?$data['menu_name']:'',
            'menu_address'=>isset($data['menu_address'])?$data['menu_address']:'',
            'menu_weight'=>isset($data['menu_weight'])?$data['menu_weight']:'',
            'production_time'=>isset($data['production_time'])?$data['production_time']:'',
            'quality_time'=>isset($data['quality_time'])?$data['quality_time']:'',
            'menu_money'=>isset($data['menu_money'])?$data['menu_money']:'',
            'menu_images_json'=>isset($data['menu_images_json'])?$data['menu_images_json']:'',
// 'if_delete'=>isset($data[''])
            'business_id'=>isset($data['business_id'])?$data['business_id']:$user['business_notice'],
// 'update_user_id'=>isset($data[''])
            'create_time'=>date('Y-m-d H:i:s',time()),
// 'update_time'=>isset($data[''])
            'menu_url'=>isset($data['menu_url'])?$data['menu_url']:'',
            'update_user_id'=>$data['ADMIN_ID'],
            'update_time'=>date('Y-m-d h:i:s'),

        );

        $re_mid = $this->WeDb->insertGetId($this->tables,$in_data);
        if(isset($data['certificate_menu'])){
            $re_cerid = $this->WeDb->insertGetId('menu_certificate',[
                'certificate_name' => "",
                'certificate_image' => $data['certificate_menu'],
                'certificate_menu_name'=> $in_data['menu_name'],
                'menu_id' => $re_mid
            ]);
        }
        if(isset($data['monitor_image'])){
            $re_monitorid = $this->WeDb->insertGetId('menu_monitor',[
                'menu_id' => $re_mid,
                'monitor_image' => $data['monitor_image'],
                'sample_name' =>  "",
                'test_location' => "",
            ]);
        }
        return ResultVo::success($re_mid);
    }
    /**
     * [编辑商品]
     * @HtttpRequest                               get|post
     * @Author       GNLEON
     * @Param
     * @DateTime     2021-04-07T16:54:32+0800
     * @LastTime     2021-04-07T16:54:32+0800
     * @return       [type]                        [description]
     */
    public function edit(){
        $data = $this->request->param('');
        if(!isset($data['id'])){return ResultVo::error();}
        $id = $data['id'];
        $up_data = array(
            'menu_name'=>isset($data['menu_name'])?$data['menu_name']:'',
            'menu_address'=>isset($data['menu_address'])?$data['menu_address']:'',
            'menu_weight'=>isset($data['menu_weight'])?$data['menu_weight']:'',
            'production_time'=>isset($data['production_time'])?$data['production_time']:'',
            'quality_time'=>isset($data['quality_time'])?$data['quality_time']:'',
            'menu_money'=>isset($data['menu_money'])?$data['menu_money']:'',
            'menu_images_json'=>isset($data['menu_images_json'])?$data['menu_images_json']:'',
            'business_id'=>isset($data['business_id'])?$data['business_id']:'',
            'update_user_id'=>isset($data['update_user_id'])?$data['update_user_id']:'',
            //'create_time'=>date('Y-m-d H:i:s',time()),
            'update_time'=>date('Y-m-d H:i:s',time()),
            'menu_url'=>isset($data['menu_url'])?$data['menu_url']:'',
        );
        $result = $this->WeDb->update($this->tables,'id = '.$data['id'],$up_data);
        return ResultVo::success($data['id']);
    }

    /**
     * 删除商品
     * @HtttpRequest                               get|post
     * @Param
     * @DateTime     2021-04-08T16:50:02+0800
     * @LastTime     2021-04-08T16:50:02+0800
     * @return       [type]                        [description]
     */
    public function del(){
        $data = $this->request->param('');
        $id = $data['id'];
        $menu_details = $this->WeDb->find($this->tables,'id = '.$id.' and if_delete !=1');
        if(!$menu_details){return ResultVo::error("商品不存在");}
        $result = $this->WeDb->update($this->tables,'id = '.$id,['if_delete'=>1]);
        return ResultVo::success();
    }

}
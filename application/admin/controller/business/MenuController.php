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
        $where = '';
        $search[0] = !empty($data['state'])?'state = '.$data['state']:'';
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
        				->select()
        				->toarray();
        //$list = $this->WeDb->selectView($this->tables,$where);
        $total =  $this->WeDb->totalView($this->tables,$where);
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

    

}
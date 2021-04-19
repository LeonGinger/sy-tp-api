<?php
namespace app\admin\controller\source;

use app\admin\controller\BaseCheckUser;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use app\model\SourceLog;
use think\db;

/**
 * 溯源码查询管理
 * lastTime:2021年4月13日11:21:49
 */
class CertifyesultController extends BaseCheckUser
{
	/**
	 * [index 查询记录]

	 * @Param
	 * @DateTime     2021-04-13T11:22:03+0800
	 * @LastTime     2021-04-13T11:22:03+0800
	 * @return       [type]                        [description]
	 */
	public function index(){
		$data = $this->request->param('');

        $order = isset($data['order'])?$data['order']:'create_time desc';

        $where = '';
        $search[0] = !empty($data['source_code'])?'source_code = "'.$data['source_code'].'"':'';
        $search[1] = !empty($data['user_id'])?'user_id ='.$data['user_id']:'';
        $search[2] = !empty($data['business_id'])?'business_id = '.$data['business_id']:'';
        // $search[2] = !empty($data['verify_if'])?'verify_if = '.$data['verify_if']:'';
        
        foreach ($search as $key => $value) {
            # code...
            if($value){
                $where=$where.$value.' and ';
            }
        }
        $where=substr($where,0,strlen($where)-5);
       	//hasBusiness
        $list = SourceLog::with(['Gmemu','Guser'])
        				->where($where)
        				->page($data['page'],$data['size'])
                        ->order($order)
        				->select()
        				->toarray();
        //$list = $this->WeDb->selectView($this->tables,$where);
        $total =  $this->WeDb->totalView("view_source_log",$where);
        return ResultVo::success(['list'=>$list,'total'=>$total]);
	}
	/**
	 * [souce_del 删除查询记录]
	 * @Param
	 * @DateTime     2021-04-13T12:01:10+0800
	 * @LastTime     2021-04-13T12:01:10+0800
	 * @return       [type]                        [description]
	 */
	public function souce_del(){
		$data = $this->request->param();
		$id = $data['id'];
		if(!$id){return ResultVo::error();}
		$result = db::table('source_log')->where('id in ('.$id.')')->delete();
		return ResultVo::success();
	}
	/**
	 * [total_echarts 查询统计]
	 * @Param
	 * @DateTime     2021-04-13T14:14:34+0800
	 * @LastTime     2021-04-13T14:14:34+0800
	 * @return       [type]                        [description]
	 */
	public function total_echarts(){
		$re_data = $this->request->param('');

        $where = '';
        if(!empty($re_data['month'])){
			$Y = date('Y', $re_data['month']);
			$M = date('m', $re_data['month']);
			$date = mFristAndLast($Y,$M);
			$search[0] = 'track_time BETWEEN "'.date('Y-m-d H:i:s',$date['firstday']).'" and "'.date('Y-m-d H:i:s',$date['lastday']).'"';
        }else{
        	$search[0] = "";
        }
        $search[1] = !empty($this->adminInfo['business_notice'])?"business_id = {$this->adminInfo['business_notice']}":'';

        foreach ($search as $key => $value) {
            # code...
            if($value){
                $where=$where.$value.' and ';
            }
        }
        $where=substr($where,0,strlen($where)-5);

		$Topten_search = db::table('view_source_log')->field('id,menu_id,menu_name,sum(track) as track')
												->where($where)
												->order('track desc')
												->limit(10)
												->group('menu_id')
												->select();

												
		if($Topten_search){
			// $menu_id = array_column($Topten_search, "menu_id");
			foreach ($Topten_search as $key => $value) {
				# code...
				$Topten_search[$key]['first_count'] = db::table('source_log')->where('menu_id = '.$value['menu_id'].' and track = 1')->count();
			}

		}

		$data = array(
			'top_ten'=>$Topten_search,
		);
		return ResultVo::success($data);
	}

}
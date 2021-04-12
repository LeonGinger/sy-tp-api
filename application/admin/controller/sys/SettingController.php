<?php
namespace app\admin\controller\sys;

use app\admin\controller\BaseCheckUser;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use redis\Redis;
use think\db;
use think\facade\Config;
/**
 * 后台设置相关
 */
class SettingController extends BaseCheckUser
{
	/**
	 * [sys_get 获取网站配置]
	 * @Param
	 * @DateTime     2021-04-12T15:46:23+0800
	 * @LastTime     2021-04-12T15:46:23+0800
	 * @return       [type]                        [description]
	 */
	public function sys_get(){
		$result = Config::get('webside.');
		return ResultVo::success($result);
	}
	/**
	 * [sys_set 设置网站配置]
	 * @Author       GNLEON
	 * @Param
	 * @DateTime     2021-04-12T15:20:20+0800
	 * @LastTime     2021-04-12T15:20:20+0800
	 * @return       [type]                        [description]
	 */
	public function sys_set(){
		$re_data = $this->request->param('');
		$conf = array(
			'website'=>$re_data['website'],
			'sitelogo'=>$re_data['sitelogo'],
		);
		$pat = [];
		$rep = [];
		foreach ($conf as $key => $value) {
			# code...
			if(!empty($value)){
				array_push($pat,$key);
				array_push($rep,$value);
			}
		}
		$res = setconfig($pat, $rep,$file='webside');
		return ResultVo::success();

	}
	/**************************************************************************************/
	/**
	 * [porblem_index 常见问题列表]
	 * @Param
	 * @DateTime     2021-04-12T10:30:05+0800
	 * @LastTime     2021-04-12T10:30:05+0800
	 * @return       [type]                        [description]
	 */
	public function porblem_index(){
		$re_data = $this->request->param('');

		$list = $this->WeDb->selectView('common_problem');
		$total = $this->WeDb->totalView('common_problem');
		$data = array(
			'list'=>$list,
			'total'=>$total,
		);
		return ResultVo::success($data);
		
	}
	/**
	 * [porblem_add 添加问题]
	 * @Param
	 * @DateTime     2021-04-12T10:50:53+0800
	 * @LastTime     2021-04-12T10:50:53+0800
	 * @return       [type]                        [description]
	 */
	public function porblem_add(){
		$re_data = $this->request->param('');
		$in_data = array(
			'title' => $re_data['title'],
			'content' => $re_data['content'],
			'onoff' => $re_data['onoff'],
			'orderid'=>$re_data['orderid'],
		);
		$resutl = $this->WeDb->insertGetId('common_problem',$in_data);
		return ResultVo::success($resutl);

	}
	/**
	 * [problem_edit 修改常见问题]
	 * @Param
	 * @DateTime     2021-04-12T10:33:48+0800
	 * @LastTime     2021-04-12T10:33:48+0800
	 * @return       [type]                        [description]
	 */
	public function problem_edit(){
		$re_data = $this->request->param('');
		$id = $re_data['id'];
		if(!$id){return ResultVo::error();}
		if(!empty('title')){$up_data['title'] = $re_data['title'];}
		if(!empty('content')){$up_data['content'] = $re_data['content'];}
		if(!empty('onoff')){$up_data['onoff'] = $re_data['onoff'];}
		if(!empty('orderid')){$up_data['orderid'] = $re_data['orderid'];}
		// $up_data = array(
		// 	'title' => $re_data['title'],
		// 	'content' => $re_data['content'],
		// 	'onoff' => $re_data['onoff'],
		// 	'orderid'=>$re_data['orderid'],
		// );
		$resutl = $this->WeDb->update('common_problem','id = '.$id,$up_data);
		return ResultVo::success();
	}
	/**
	 * [problem_del 删除问题记录]
	 * @Param
	 * @DateTime     2021-04-12T10:39:20+0800
	 * @LastTime     2021-04-12T10:39:20+0800
	 * @return       [type]                        [description]
	 */
	public function problem_del(){
		$re_data = $this->request->param('');
		$id = $re_data['id'];
		if(!$id){return ResultVo::error();}
		$resutl = db::table('common_problem')->where('id = '.$id)->delete();
		return ResultVo::success();

	}
	/**************************************************************************************/
		/**
	 * [porblem_index 商家须知列表]
	 * @Param
	 * @DateTime     2021-04-12T10:30:05+0800
	 * @LastTime     2021-04-12T10:30:05+0800
	 * @return       [type]                        [description]
	 */
	public function BusinessNotice_index(){
		$re_data = $this->request->param('');
		$list = $this->WeDb->find('business_notice','id = 1');
		$data = array(
			'list'=>$list,
		);

		// $list = $this->WeDb->selectView('business_notice');
		// $total = $this->WeDb->totalView('business_notice');
		// $data = array(
		// 	'list'=>$list,
		// 	'total'=>$total,
		// );
		return ResultVo::success($data);
		
	}
	/**
	 * [porblem_add 添加问题]
	 * @Param
	 * @DateTime     2021-04-12T10:50:53+0800
	 * @LastTime     2021-04-12T10:50:53+0800
	 * @return       [type]                        [description]
	 */
	public function BusinessNotice_add(){
		$re_data = $this->request->param('');
		$in_data = array(
			'title' => $re_data['title'],
			'content' => $re_data['content'],
			'onoff' => $re_data['onoff'],
			'orderid'=>$re_data['orderid'],
		);
		$result = $this->WeDb->insertGetId('business_notice',$in_data);
		return ResultVo::success($result);

	}
	/**
	 * [problem_edit 修改常见问题]
	 * @Param
	 * @DateTime     2021-04-12T10:33:48+0800
	 * @LastTime     2021-04-12T10:33:48+0800
	 * @return       [type]                        [description]
	 */
	public function BusinessNotice_edit(){
		$re_data = $this->request->param('');
		$id = $re_data['id'];
		if(!$id){return ResultVo::error();}
		if(!empty('content')){$up_data['content'] = $re_data['content'];}
		if(!empty('onoff')){$up_data['onoff'] = $re_data['onoff'];}
		// $up_data = array(
		// 	'content' => $re_data['content'],
		// 	'onoff' => $re_data['onoff'],
		// 	'orderid'=>$re_data['orderid'],
		// );
		$resutl = $this->WeDb->update('business_notice','id = '.$id,$up_data);
		return ResultVo::success();
	}
	/**
	 * [problem_del 删除问题记录]
	 * @Param
	 * @DateTime     2021-04-12T10:39:20+0800
	 * @LastTime     2021-04-12T10:39:20+0800
	 * @return       [type]                        [description]
	 */
	public function BusinessNotice_del(){
		$re_data = $this->request->param('');
		$id = $re_data['id'];
		if(!$id){return ResultVo::error();}
		$resutl = db::table('business_notice')->where('id = '.$id)->delete();
		return ResultVo::success();

	}
}
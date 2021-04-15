<?php
namespace app\admin\controller\sys;

use app\admin\controller\BaseCheckUser;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use redis\Redis;
use think\db;
use think\facade\Config;
use databackup\Backup;
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
	/**
	 * [dump_base 备份数据库列表]
	 * @Param
	 * @DateTime     2021-04-15T16:04:14+0800
	 * @LastTime     2021-04-15T16:04:14+0800
	 * @return       [type]                        [description]
	 * @remark https://blog.csdn.net/ging_ko/article/details/88336254
	 */
	public function index_base(){
		$db= new Backup(Config::get('databackup'));
		//数据库备份列表
		$backup_list = $db->fileList();
		$re_data = ['list'=>$backup_list,'total'=>count($backup_list)];
		return ResultVo::success($re_data);
	}
	/**
	 * [dump_base 备份数据库]
	 * @auth         true
	 * @throws       \think\Exception
	 * @throws       \think\exception\PDOException
	 * @HtttpRequest                               get|post
	 * @Author       GNLEON
	 * @Param
	 * @DateTime     2021-04-15T16:16:20+0800
	 * @LastTime     2021-04-15T16:16:20+0800
	 * @return       [type]                        [description]
	 */
	public function dump_base(){
		$db= new Backup(Config::get('databackup'));

		$table_list = $db->dataList();
		$file=['name'=>date('Ymd-His'),'part'=>1];
		$file=['name'=>date('Ymd-His'),'part'=>1];
		foreach ($table_list as $key => $value) {
		 $res=  $db->setFile($file)->backup($value['name'], 0);
		}

		

	}
	/**
	 * [down_base 下载数据库]
	 * @Param
	 * @DateTime     2021-04-15T16:11:17+0800
	 * @LastTime     2021-04-15T16:11:17+0800
	 * @return       [type]                        [description]
	 */
	public function down_base(){
		$time = $this->request->param('time');
		if($time=='0'){return ResultVo::error();}
		$db= new Backup(Config::get('databackup'));
		$db->downloadFile($time);

	}
	/**
	 * [del_base 删除备份数据库]
	 * @Param
	 * @DateTime     2021-04-15T16:11:26+0800
	 * @LastTime     2021-04-15T16:11:26+0800
	 * @return       [type]                        [description]
	 */
	public function del_base(){
		$time = $this->request->post('time');
		if($time=='0'){return ResultVo::error();}
		$db= new Backup(Config::get('databackup'));
		$db->delFile($time);

	}
}
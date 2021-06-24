<?php
namespace app\admin\controller\sys;

use app\admin\controller\BaseCheckUser;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use redis\Redis;
use think\db;
use think\facade\Config;
use databackup\Backup;
use think\Queue;
use app\model\Unit;
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
	//无需路由
	public function sys_getno(){
		$res = $this->sys_get();
		return $res;
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
			'copyright'=>$re_data['copyright'],
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

		$jobHandlerClassName  = 'app\admin\controller\job\System';
        $jobQueueName        = "dumpBase";

		$table_list = $db->dataList();
		$file=['name'=>date('Ymd-His'),'part'=>1];
		foreach ($table_list as $key => $value) {
			$job_data = array(
				'file' => $file,
				'table' => $value['name'],
				'jobname' => 'dumpbase',
			);
        	$isPushed = Queue::push($jobHandlerClassName, $job_data, $jobQueueName);

		 // $res=  $db->setFile($file)->backup($value['name'], 0);
		}
		return ResultVo::success();
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
		return ResultVo::success();

	}
	/**
	 * [del_base 删除备份数据库]
	 * @Param
	 * @DateTime     2021-04-15T16:11:26+0800
	 * @LastTime     2021-04-15T16:11:26+0800
	 * @return       [type]                        [description]
	 */
	public function del_base(){
		$data = $this->request->post('');
		$time = $data['time'];
		$type = $data['type'];
		if($time=='0'){return ResultVo::error();}
		$db= new Backup(Config::get('databackup'));
		//批量
		if($type=="list"){
			$time_list = explode(",",$time);
			var_dump($time_list);

			foreach ($time_list as $key => $value) {
				# code...
			var_dump($value);
				$db->delFile($value);
				
			}
			exit();
			return ResultVo::success();
		}
		//单个
		$db->delFile($time);
		return ResultVo::success();

	}


	/****************************************设置单位相关接口 开始*******************************************/
	/**
	 * 单位列表
	 * @HtttpRequest                               get|
	 * @Author       GNLEON
	 * @Param
	 * @DateTime     2021-06-24T09:44:45+0800
	 * @LastTime     2021-06-24T09:44:45+0800
	 * @return       [type]                        [description]
	 */
	public function unit_list(){
		$data = $this->request->param('');
		$where = '';
		$search[0] = !empty($data['unit_class'])?'unit_class = '.$data['unit_class']:'';
		$search[1] = !empty($data['business_id'])?"business_id = {$data['business_id']}":'';
		$order = 'id desc';

        foreach ($search as $key => $value) {
            # code...
            if($value){
                $where=$where.$value.' and ';
            }
        }
        $where=substr($where,0,strlen($where)-5);

        $list = Unit::with(['hasBusiness'])
        			->where($where)
            		->page($data['page'],$data['size'])
            		->order($order)
            		->select()
            		->toarray();
        $total =  $this->WeDb->totalView("unit",$where);
 		return ResultVo::success(['list'=>$list,'total'=>$total]);
	}
	/**
	 * 添加单位
	 * @HtttpRequest                               |post
	 * @Author       GNLEON
	 * @Param
	 * @DateTime     2021-06-24T09:44:54+0800
	 * @LastTime     2021-06-24T09:44:54+0800
	 * @return       [type]                        [description]
	 */
	public function unit_save(){
		$data = $this->request->param('');

		if($this->adminInfo['role_id']!='1' && empty($data['business_id'])){
			return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
		}
		$indata = array(
			'unit_name' => $data['unit_name'],
			'unit_class' => $data['unit_class'],
			'business_id' => $data['business_id'],
		);
		$result = $this->WeDb->insertGetId('unit',$indata);
		return ResultVo::success();
	}
	/**
	 * 修改单位
	 * @HtttpRequest                               |post
	 * @Author       GNLEON
	 * @Param
	 * @DateTime     2021-06-24T09:45:07+0800
	 * @LastTime     2021-06-24T09:45:07+0800
	 * @return       [type]                        [description]
	 */
	public function unit_update(){
		$data = $this->request->param('');

		if(empty($data['id'])){return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);}

		$updata = array(
			'unit_name' => $data['unit_name'],
			'unit_class' => $data['unit_class'],
		);
		$result = $this->WeDb->update('unit','id = '.$data['id'],$updata);
		return ResultVo::success();
	}
	/**
	 * 删除单位(可多选)
	 * @HtttpRequest                               |post
	 * @Author       GNLEON
	 * @Param
	 * @DateTime     2021-06-24T09:45:17+0800
	 * @LastTime     2021-06-24T09:45:17+0800
	 * @return       [type]                        [description]
	 */
	public function unit_remove(){
		$data = $this->request->param('');
		$result = Unit::where('id in ('.$data['id'].')')->delete();
		return ResultVo::success($result); 
		
	}
	/****************************************设置单位相关接口 结束*******************************************/

}
<?php
namespace app\admin\controller\sys;

use app\admin\controller\BaseCheckUser;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use redis\Redis;
use think\db;
/**
 * 其他相关接口
 */
class AdminController extends BaseCheckUser
{
	private $tables = 'business';
	/**
	 * 首页面板数据
	 * @HtttpRequest                               get
	 * @Author       GNLEON
	 * @Param
	 * @DateTime     2021-03-29T14:45:10+0800
	 * @LastTime     2021-03-29T14:45:10+0800
	 * @return       [type]                        [description]
	 */
	public function get_index_data(){
   		$BeginMonthDate=date('Y-m-01', strtotime(date("Y-m-d")));
   		$EndmonthDate = date('Y-m-d', strtotime("$BeginMonthDate +1 month -1 day"));
		$data = array();

		if($this->adminInfo['role_id']=='1'){
			$data['business_count'] = $this->WeDb->totalView($this->tables,"delete_time is null");
			$data['businessapply_count'] = $this->WeDb->totalView($this->tables,"delete_time is null and verify_if = 3");
			$data['menu_count'] = $this->WeDb->totalView('menu',"if_delete = 0");
			$data['menumonth_count'] = $this->WeDb->totalView('menu',"if_delete = 0 and create_time between \"".$BeginMonthDate."\" and \"".$EndmonthDate."\"");
			$data['user_count'] = $this->WeDb->totalView('user',"delete_time is null");
			$data['usermonth_count'] = $this->WeDb->totalView('user',"delete_time is null and create_time between \"".$BeginMonthDate."\" and \"".$EndmonthDate."\"");

		}

		if($this->adminInfo['business_notice']){

			$data['menu_count'] = $this->WeDb->totalView('menu',"if_delete = 0 and business_id = {$this->adminInfo['business_notice']}");
			$data['menumonth_count'] = $this->WeDb->totalView('menu',"if_delete = 0 and business_id = {$this->adminInfo['business_notice']} and create_time between \"".$BeginMonthDate."\" and \"".$EndmonthDate."\"");
			$data['user_count'] = $this->WeDb->totalView('user',"delete_time is null and business_notice = {$this->adminInfo['business_notice']}");
			$data['usermonth_count'] = $this->WeDb->totalView('user',"delete_time is null and business_notice = {$this->adminInfo['business_notice']} and create_time between \"".$BeginMonthDate."\" and \"".$EndmonthDate."\"");
			
		}


		return ResultVo::success($data);

	}

	/**
	 * [echarts_list 历史面板统计图]
	 * @HtttpRequest                               get
	 * @Author       GNLEON
	 * @Param
	 * @DateTime     2021-03-29T15:27:31+0800
	 * @LastTime     2021年4月23日13:19:41
	 * @return       [type]                        [description]
	 */
	public function echarts_list(){
		$data = $this->request->param('');
		$month = $data['month'];
		if(!$month){
			// return ResultVo::error();
			$month = date('Y-m-01',time());
		}
		$first_day = date('Y-m-d',strtotime($month));
		$end_day = date("Y-m-d",strtotime("$first_day +1 month -1 day"));

		$sql = $this->get_chartssql($first_day,$end_day);
		$res_user= db::query($sql[0]);
		$res_source = db::query($sql[1]);
		foreach ($res_user as $key => $value) {
			# code...
			$res_user[$key]['count_source'] = $res_source[$key]['count_source'];
		}
		return ResultVo::success($res_user);


	}
	private function get_chartssql($start,$end){

		$sql_user = "SELECT ".
			"calendar_copy.datelist, ".
			"count(user.id) as count_user ".
			"FROM ".
			"calendar_copy ".
			"LEFT JOIN `user` ON calendar_copy.datelist = date_format(`user`.create_time,'%Y-%m-%d') ".
			"where datelist between \"$start\" and \"$end\" ".
			"GROUP BY calendar_copy.datelist ";

		$sql_source = "SELECT ".
				"calendar_copy.datelist, ".
				"count(source_log.id) as count_source ".
				"FROM ".
				"calendar_copy ".
				"LEFT JOIN `source_log` ON calendar_copy.datelist = date_format(`source_log`.create_time,'%Y-%m-%d') ".
				"where datelist between \"$start\" and \"$end\" ".
				"GROUP BY calendar_copy.datelist ";
		return [$sql_user,$sql_source];
	}
}
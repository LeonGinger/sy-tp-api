<?php
namespace app\admin\controller\sys;

use app\admin\controller\BaseCheckUser;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use redis\Redis;
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
	 * [echarts_list 面板统计图]
	 * @HtttpRequest                               get
	 * @Author       GNLEON
	 * @Param
	 * @DateTime     2021-03-29T15:27:31+0800
	 * @LastTime     2021年4月23日13:19:41
	 * @return       [type]                        [description]
	 */
	public function echarts_list(){

	}
}
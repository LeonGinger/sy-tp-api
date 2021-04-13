<?php
namespace app\admin\controller\wechat;

use app\admin\controller\BaseCheckUser;
use app\admin\controller\Base;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;

use think\Queue;
use redis\Redis;
/**
 * 公众号粉丝管理
 */
class FansController extends BaseCheckUser
{

	/**
	 * [index 粉丝列表]
	 * @Param
	 * @DateTime     2021-04-09T14:06:16+0800
	 * @LastTime     2021-04-09T14:06:16+0800
	 * @return       [type]                        [description]
	 */
	public function index(){


		$data = $this->request->param();

		$where = '';
        $search[0] = '';
        //$search[0] = !empty($data['business_notice'])?'business_notice = '.$data['business_notice']:'';
        foreach ($search as $key => $value) {
              # code...
              if($value){
                  $where=$where.$value.' and ';
              }
        }
    
        $where=substr($where,0,strlen($where)-5);
        if($data['page']==1){$data['page']=0;}
        if($data['page']>1){$data['page'] = $data['page']-1;}
    
        $list = $this->WeDb->selectView('view_wechat_fans',$where,'*',$data['page'],$data['size'],'create_at desc');
        $total = $this->WeDb->totalView('view_wechat_fans',$where,'id');
		return ResultVo::success(['list'=>$list,'total'=>$total]);
	}

	/**
	 * [sysfans 同步公众号粉丝]
	 * @Author       GNLEON
	 * @Param
	 * @DateTime     2021-04-09T14:19:41+0800
	 * @LastTime     2021-04-09T14:19:41+0800
	 * @return       [type]                        [description]
	 * @remark   根据粉丝数量-控制循环阈值
	 */
	public function sysfans(){
        $redis = new Redis;

        $jobHandlerClassName  = 'app\admin\controller\job\Wechat';

        $jobQueueName        = "SysncFans";

        $list = $this->Wechat_tool->fanslist();
        if(!$list){return ResultVo::error();}
        $openid_list = $list['data']['openid'];
        $sys_num = ceil($list['total']/200);
       
        $need_count = $redis->llen('queues:SysncFans')?:0;
        $redis->set('sy_sysfans_count',$sys_num+$need_count);
        $start_index = 200;

        for ($i=0; $i <$sys_num ; $i++) { 
        	# code...
        	$job_data['data'] = array_slice($openid_list,$i*$start_index,$start_index);
        	$job_data['jobname'] = "SysncFans";
        	$isPushed = Queue::push($jobHandlerClassName, $job_data, $jobQueueName);
        	if ($isPushed == false) {
        	 	break;
        	}
        }

       return ResultVo::success('添加同步粉丝任务成功');

	}
    /**
     * [fans_state 获取粉丝同步任务状态]
     * @auth         true
     * @throws       \think\Exception
     * @throws       \think\exception\PDOException
     * @HtttpRequest                               get|post
     * @Author       GNLEON
     * @Param
     * @DateTime     2021-04-13T10:43:24+0800
     * @LastTime     2021-04-13T10:43:24+0800
     * @return       [type]                        [description]
     */
    public function fans_state(){
        $redis = new Redis;
        $count = $redis->get('sy_sysfans_count');
        $result = $redis->llen('queues:SysncFans');
        try{
            $is_done = 100-round($result/$count*100,2).'%';
        } catch (\Throwable $th) {
            $is_done = '100%';
        }
        $data = ['percen'=>$is_done,'need'=>$result];
        return ResultVo::success($data);
    }
}
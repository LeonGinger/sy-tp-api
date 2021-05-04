<?php

namespace app\command;

use app\common\vo\ResultVo;
use think\db;
use think\Queue;
use redis\Redis;
//
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Log;

use app\common\utils\WeDbUtils;

/**
 * 商家事务相关
 */
class Business extends Command
{
    private $table = 'business';

    protected function configure(){
        $this->setName('Business')->setDescription("计划任务 SendMessage");
    }
 
    //调用SendMessage 这个类时,会自动运行execute方法
    protected function execute(Input $input, Output $output){
        $output->writeln('Date Crontab job start...');
        /*** 这里写计划任务列表集 START ***/
 
        $this->business_push();//
 
        /*** 这里写计划任务列表集 END ***/
        $output->writeln('Date Crontab job end...');
    }


    // 发送每日的统计推送
	public function business_push(){
        $WeDb = WeDbUtils::getInstance();
		$business_userAll = $WeDb->selectView('user','role_id = 2');
		for($i = 0;$i<count($business_userAll);$i++){
			$jobHandlerClassName  = 'app\admin\controller\job\businessAll';
			$jobQueueName = "business_push";
			$num = 50000;
			$redis = new Redis();
			$redis->set('has_create', $num);
			$redis->set('gotostatus', 1);
			$isPushed = Queue::push($jobHandlerClassName, $business_userAll[$i], $jobQueueName);

		}
        return ResultVo::success($isPushed);
	}
}

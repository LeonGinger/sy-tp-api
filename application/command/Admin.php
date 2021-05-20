<?php

namespace app\command;

// use app\common\vo\ResultVo;
// use think\db;
// use think\Queue;
use redis\Redis;
//
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Log;

/**
 * 后台相关定时事务
 */
class Admin extends Command
{
    // private $table = 'business';

    protected function configure(){
        $this->setName('Admin')->setDescription("计划任务 溯源后台管理");
    }
 
    //调用SendMessage 这个类时,会自动运行execute方法
    protected function execute(Input $input, Output $output){
        $output->writeln('Date Crontab job start...');
        /*** 这里写计划任务列表集 START ***/
 
        $this->clear_pici();//
 
        /*** 这里写计划任务列表集 END ***/
        $output->writeln('Date Crontab job end...');
    }


    //每天恢复批次redis -sy_PiciNumber
    public function clear_pici(){
        $redis = new Redis;
        $redis->set("sy_PiciNumber",0);
    }
}

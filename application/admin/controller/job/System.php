<?php
namespace app\admin\controller\job;

use think\Db;
use think\queue\Job;
use redis\Redis;
use app\common\utils\WechatUtils;
use app\common\utils\WeDbUtils;
use think\facade\Config;
use databackup\Backup;

/**
 * 系统相关
 */
class System
{
    /**
     * 微信公众号相关队列任务
     * fire方法是消息队列默认调用的方法
     * @param Job            $job      当前的任务对象
     * @param array|mixed    $data     发布任务时自定义的数据
     */
    public function fire(Job $job,$data){
        // $redis = new Redis();
        if(!$data['jobname']){$job->delete();}

        if($data['jobname']=="dumpbase"){
            $isJobDone = $this->dumpbase($data);
        }
        
        if ($isJobDone) {
            $job->delete();
            //如果任务执行成功， 记得删除任务
            
            print("<info>系统任务队列本次完成"."</info>\n");
        }else{
            if ($job->attempts() > 3) {
                //通过这个方法可以检查这个任务已经重试了几次了
                print("<warn>系统任务队列已重试3次执行,现在开始删除本任务"."</warn>\n");
                $job->delete();
            }
        }
    }
    /**
     * 根据消息中的数据进行实际的业务处理
     * @param array|mixed    $data     发布任务时自定义的数据
     * @return boolean                 任务执行的结果
     * @remark 通过file 写入到指定的文件中
     */
    private function dumpbase($data) {
 		$db= new Backup(Config::get('databackup'));
 		$res= $db->setFile($data['file'])->backup($data['table'], 0);
 		var_dump($res);
    }
}
<?php
namespace app\admin\controller\job;

use think\Db;
use think\queue\Job;
use redis\Redis;
class Demo
{
    /**
     * fire方法是消息队列默认调用的方法
     * @param Job            $job      当前的任务对象
     * @param array|mixed    $data     发布任务时自定义的数据
     */
    public function fire(Job $job,$data){
        $redis = new Redis();
        $isJobDone = $this->doHelloJob($data);

        if ($isJobDone) {
            $isok = $redis->get('gotostatus');

            if($isok==3){
               $job->delete();
            }
            //如果任务执行成功， 记得删除任务
            
            print("<info>Hello Job has been done and deleted"."</info>\n");
        }else{
            if ($job->attempts() > 3) {
                //通过这个方法可以检查这个任务已经重试了几次了
                print("<warn>Hello Job has been retried more than 3 times!"."</warn>\n");
                $job->delete();
            }
        }
    }

    /**
     * 根据消息中的数据进行实际的业务处理
     * @param array|mixed    $data     发布任务时自定义的数据
     * @return boolean                 任务执行的结果
     * 0-等待继续 1-确认继续 2-
     */
    private function doHelloJob($data) {
        // 根据消息中的数据进行实际的业务处理...
        $redis = new Redis();
        $goto = $redis->get('gotostatus');
        if($goto!=1){return true;}
        $neednum = $redis->get('has_create');
        if(!$neednum){return true;}
        if($neednum-200<0){
            $now_need = 200-(abs($neednum-200));
            $set_need = $now_need;
            $neednum = $redis->del('has_create');
            $is_ok = 2;
        }else{
            $now_need = $neednum-200;
            $set_need = 200;
            $neednum = $redis->set('has_create',$now_need);
            $is_ok = 0;
        }
                
        $jobData = [

        ];
        for ($i=0; $i <$set_need ; $i++) { 
            # code...
            $jobData[$i] = ['name'=>'GNLEON'.time()];            
        }
        // $name = $data['name'];
        $res = Db::table('user')->insertAll($jobData);
        if($res){
            $redis->set('gotostatus',$is_ok);
        }
        return $res;
    }
}

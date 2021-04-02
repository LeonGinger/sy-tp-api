<?php
namespace app\wap\controller\job;

use think\Db;
use think\queue\Job;
use redis\Redis;
class order
{
    /**
     * fire方法是消息队列默认调用的方法
     * @param Job            $job      当前的任务对象
     * @param array|mixed    $data     发布任务时自定义的数据
     */
    public function fire(Job $job,$data){
        $redis = new Redis();
        $isJobDone = $this->doHelloJob($data);
        // var_dump($data);
        // exit;
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

        $sourceinsert = DB::name('source')->insert($data);
        return $sourceinsert;
    }
}

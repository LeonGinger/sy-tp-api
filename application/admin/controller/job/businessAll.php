<?php
namespace app\admin\controller\job;

use think\Db;
use think\queue\Job;
use redis\Redis;
use think\facade\Config;
use app\common\utils\WechatUtils;
class businessAll
{
    /**
     * fire方法是消息队列默认调用的方法
     * @param Job            $job      当前的任务对象
     * @param array|mixed    $data     发布任务时自定义的数据
     */
    public function fire(Job $job,$data){
        $redis = new Redis();
        $isJobDone = $this->doHelloJob($data);
        print($isJobDone);
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
        return $isJobDone;
    }

    /**
     * 根据消息中的数据进行实际的业务处理
     * @param array|mixed    $data     发布任务时自定义的数据
     * @return boolean                 任务执行的结果
     * 0-等待继续 1-确认继续 2-
     */
    private function doHelloJob($data) {
        // 推送给申请人↓
        // $business = $this->WeDb->find('business','id = '.$data['business_notice']);
        $business = Db::name('business')->where('id = '.$data['business_notice'])->find();
        $time = date('Y-m-d 00:00:00');
        $go_time = date('Y-m-d H:i:s');
        // $source = $this->WeDb->selectView('source','business_id = '.$business['id'].' and storage_time BETWEEN '.$time.' and '.$go_time);
        $source = Db::name('source')->where('business_id = '.$business['id'].' and storage_time BETWEEN "'.$time.'" and "'.$go_time.'"')->select();
        $enter_number = count($source);
        // $source = $this->WeDb->selectView('source','business_id = '.$business['id'].' and deliver_time BETWEEN '.$time.' and '.$go_time);
        $source = Db::name('source')->where('business_id = '.$business['id'].' and deliver_time BETWEEN "'.$time.'" and "'.$go_time.'"')->select();
        $out_number = count($source);
        $da_content = [
            'first' => ['value' => '本日溯源统计', 'color' => "#000000"],
            'keyword1' => ['value' => $business['business_name'], 'color' => "#000000"],
            'keyword2' => ['value' => date('Y-m-d H:i:s'), 'color' => "#000000"],
            'keyword3' => ['value' => "今日 您的出库数量为：{$enter_number}您的入库数量为：{$out_number}", 'color' => "#000000"],
            'remark' => ['value' => "溯源系统", 'color' => "#000000"],
        ];
        $data = [
            'Template_id' => 'REcBoBaBDiK4jO0RRhT6jjAFK04wIuEO0G4_CQht0Vs',
            'openid' => $data['open_id'],
            'url' => Config::get('domain_h5').'#/pages/my/my',
            'content' => $da_content,
        ];
        $wech = WechatUtils::getInstance();
        $return = $wech->sendMessage($data);
        // var_dump()
        // * //
        return $return;
    }
}

<?php

namespace app\admin\controller\source;

use app\admin\controller\BaseCheckUser;
use app\admin\controller\Base;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use app\model\source;
use think\Queue;
use redis\Redis;
/**
 * 溯源码
 */
class SourcecodeController extends BaseCheckUser
{
    protected $redis = null;
    public function initialize(){
        parent::initialize();
        $this->redis = new Redis();
    }
    /**
     * 溯源码队列测试
     */
    public function createcode(){
        // 1.当前任务将由哪个类来负责处理。
        //   当轮到该任务时，系统将生成一个该类的实例，并调用其 fire 方法
        $jobHandlerClassName  = 'app\admin\controller\job\Demo';
        // 2.当前任务归属的队列名称，如果为新队列，会自动创建
        $jobQueueName  	  = "helloJobQueue";
        // 3.当前任务所需的业务数据 . 不能为 resource 类型，其他类型最终将转化为json形式的字符串
        //   ( jobData 为对象时，需要在先在此处手动序列化，否则只存储其public属性的键值对)
        $num = 50000;
        $this->redis->set('has_create',$num);
        $this->redis->set('gotostatus',1);
//         $jobData = [
// 
//         ];
//         for ($i=0; $i <=200 ; $i++) { 
//             # code...
//             $jobData[$i] = ['name'=>'GNLEON'.time()];            
//         }
        $job_Data = [
            'num'=>$num,
        ];
        // 4.将该任务推送到消息队列，等待对应的消费者去执行
        $isPushed = Queue::push( $jobHandlerClassName , $job_Data , $jobQueueName );
        // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
        if( $isPushed !== false ){
            return ResultVo::success('成功添加任务');
            echo date('Y-m-d H:i:s') . " a new Hello Job is Pushed to the MQ"."<br>";
        }else{
            echo 'Oops, something went wrong.';
        }
    }

    /**
     * 生成状态
     * think queue:listen --queue helloJobQueue
     */
    public function createcodestatus(){
        $res = $this->redis->get('has_create');
        $status = $this->redis->get('gotostatus');
        if($status==0){
            $this->redis->set('gotostatus',1);
            $jobHandlerClassName  = 'app\admin\controller\job\Demo';
            $jobQueueName  	  = "helloJobQueue";
            $job_Data = [
                'num'=>$res,
            ];
            $isPushed = Queue::push( $jobHandlerClassName , $job_Data , $jobQueueName );
        }
        var_dump($res);
        var_dump($status);
    }
    public function order_list(){
        $data = $this->request->param('');
        // var_dump($data);
        // exit;
        $userid = $data['ADMIN_ID'];
        $user = $this->WeDb->find('user',"id = {$userid}");
        $field = "*";
        // var_dump($order);
        // exit;
        $where = '';
        $search[0] = !empty($data['ADMIN_ID'])?'business_id = '.$user['business_notice']:'';
        // $search[1] = !empty($data['role_id'])?'role_id in ('.$data['role_id'].')':'';
        // $search[2] = !empty($data['phone'])?'phone = '.trim($data['phone']):'';
        // $search[3] = !empty($data['username'])?'username like "%'.trim($data['username']).'%"':'';
        // $search[4] = !empty($data['business_name'])?'business_name like "%'.trim($data['business_name']).'%"':'1=1';
        foreach ($search as $key => $value) {
            # code...
            if($value){
                $where=$where.$value.' and ';
            }
        }
        $where=substr($where,0,strlen($where)-5);
        $order = $this->WeDb->selectView('order',$where,$field,$data['page'],$data['limit'],'create_time desc');
        for($i=0;$i<count($order);$i++){
            $user_in = $this->WeDb->find('user',"id = {$order[$i]['user_id']}");
            $order[$i]['user_name'] = $user_in['username'];
            $source_json = json_decode($order[$i]['source_injson'], true);
            // var_dump($source_json);
            // exit;
            $max_number = 0;
            for($o=0;$o<count($source_json);$o++){
                $max_number += (int)$source_json[$o]['number'] * (int)$source_json[$o]['menu_number'] +1;
                // $max_number += 1; 
            }
            $order[$i]['source_code_number'] = $max_number;
            // exit;
            $li_source = $this->WeDb->find('source','order_number = "'.$order[$i]['order_number'].'"');

            $Max_code_number = $li_source['order_key_number'];
            if($Max_code_number == null){
                $Max_code_number = 0;
            }
            $order[$i]['order_code_number'] = $Max_code_number;
        }
        $total = $this->WeDb->totalView('order',$where,'id');
        return ResultVo::success(['list'=>$order,'total'=>$total]);
    }
    public function source_list(){
        $data = $this->request->param('');
        // var_dump($data);
        // exit;
        $userid = $data['ADMIN_ID'];
        $user = $this->WeDb->find('user',"id = {$userid}");
        $field = "*";
        // var_dump($order);
        // exit;
        $where = '';
        $search[0] = !empty($data['ADMIN_ID'])?'business_id = '.$user['business_notice']:'';
        // $search[1] = !empty($data['role_id'])?'role_id in ('.$data['role_id'].')':'';
        // $search[2] = !empty($data['phone'])?'phone = '.trim($data['phone']):'';
        // $search[3] = !empty($data['username'])?'username like "%'.trim($data['username']).'%"':'';
        // $search[4] = !empty($data['business_name'])?'business_name like "%'.trim($data['business_name']).'%"':'1=1';
        foreach ($search as $key => $value) {
            # code...
            if($value){
                $where=$where.$value.' and ';
            }
        }
        $where=substr($where,0,strlen($where)-5);
        $source = $this->WeDb->selectView('source',$where,$field,$data['page'],$data['limit'],'id desc');
        $total = $this->WeDb->totalView('order',$where,'id');
        for($i = 0;$i<count($source);$i++){
            if($source[$i]['enter_user_id'] == null){
                $source[$i]['state'] = '未入库';
            }else if($source[$i]['out_user_id'] == null){
                $source[$i]['state'] = '未出库';
            }else if($source[$i]['out_user_id'] != null){
                $source[$i]['state'] = '已入库';
            }
            if($source[$i]['source_number'] == null){
                $source[$i]['source_number'] = 0;
            }
        }
        return ResultVo::success(['list'=>$source,'total'=>$total]);
    }
}
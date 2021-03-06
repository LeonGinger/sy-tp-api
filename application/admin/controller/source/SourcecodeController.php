<?php

namespace app\admin\controller\source;

use app\admin\controller\BaseCheckUser;
use app\admin\controller\Base;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use app\model\source;
use think\Queue;
use redis\Redis;
use think\db;

/**
 * 溯源码
 */
class SourcecodeController extends BaseCheckUser
{
    protected $redis = null;
    public function initialize()
    {
        parent::initialize();
        $this->redis = new Redis();
    }
    /**
     * 溯源码队列测试
     */
    public function createcode()
    {
        // 1.当前任务将由哪个类来负责处理。
        //   当轮到该任务时，系统将生成一个该类的实例，并调用其 fire 方法
        $jobHandlerClassName  = 'app\admin\controller\job\Demo';
        // 2.当前任务归属的队列名称，如果为新队列，会自动创建
        $jobQueueName        = "helloJobQueue";
        // 3.当前任务所需的业务数据 . 不能为 resource 类型，其他类型最终将转化为json形式的字符串
        //   ( jobData 为对象时，需要在先在此处手动序列化，否则只存储其public属性的键值对)
        $num = 50000;
        $this->redis->set('has_create', $num);
        $this->redis->set('gotostatus', 1);
        //         $jobData = [
        // 
        //         ];
        //         for ($i=0; $i <=200 ; $i++) { 
        //             # code...
        //             $jobData[$i] = ['name'=>'GNLEON'.time()];            
        //         }
        $job_Data = [
            'num' => $num,
        ];
        // 4.将该任务推送到消息队列，等待对应的消费者去执行
        $isPushed = Queue::push($jobHandlerClassName, $job_Data, $jobQueueName);
        // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
        if ($isPushed !== false) {
            return ResultVo::success('成功添加任务');
            echo date('Y-m-d H:i:s') . " a new Hello Job is Pushed to the MQ" . "<br>";
        } else {
            echo 'Oops, something went wrong.';
        }
    }

    /**
     * 生成状态
     * think queue:listen --queue helloJobQueue
     */
    public function createcodestatus()
    {
        $res = $this->redis->get('has_create');
        $status = $this->redis->get('gotostatus');
        if ($status == 0) {
            $this->redis->set('gotostatus', 1);
            $jobHandlerClassName  = 'app\admin\controller\job\Demo';
            $jobQueueName        = "helloJobQueue";
            $job_Data = [
                'num' => $res,
            ];
            $isPushed = Queue::push($jobHandlerClassName, $job_Data, $jobQueueName);
        }
        var_dump($res);
        var_dump($status);
    }
    // 批次列表
    public function order_list()
    {
        $data = $this->request->param('');
        // var_dump($data);
        // exit;
        $userid = $this->adminInfo['id'];
        $user = $this->WeDb->find('user', "id = {$userid}");
        
        $field = "*";
        // var_dump($data['thismy']);
        // exit;
        $where = '';
        if(isset($data['thismy'])){
            if($data['thismy'] == 'true'){
                $data['set_userid'] = $user['id'];
            }
        }
        if(isset($data['username'])){
            $in_user = $this->WeDb->find('user','username like "%'.$data['username'].'%" and business_notice = '.$user['business_notice'].'');
            $data['set_userid'] = $in_user['id'];
        }
        $search[0] = !empty($userid) ? 'business_id = ' . $user['business_notice'] : '';
        $search[2] = !empty($data['set_userid']) ? 'user_id = ' . $data['set_userid'] : '';
        // $search[1] = !empty($data['role_id'])?'role_id in ('.$data['role_id'].')':'';
        // $search[2] = !empty($data['phone'])?'phone = '.trim($data['phone']):'';
        $search[1] = !empty($data['order_number'])?'order_number like "%'.trim($data['order_number']).'%"':'1=1';
        // $search[2] = !empty($data['username'])?'username like "%'.trim($data['username']).'%"':'';
        if($user['role_id'] == 1){
            $search[0] = $search[1];
            $search[1] = '';
            
        }
        // var_dump($user);
        // exit;
        foreach ($search as $key => $value) {
            # code...
            if ($value) {
                $where = $where . $value . ' and ';
            }
        }
        $where = substr($where, 0, strlen($where) - 5);
        
        $order = $this->WeDb->selectView('order', $where, $field, $data['page'], $data['limit'], 'create_time desc');
        for ($i = 0; $i < count($order); $i++) {
            $user_in = $this->WeDb->find('user', "id = {$order[$i]['user_id']}");
            $order[$i]['user_name'] = $user_in['username'];
            // exit;
            $li_source = $this->WeDb->find('source', 'order_number = "' . $order[$i]['order_number'] . '"');
            $source = $this->WeDb->selectView('source','order_number = "'.$order[$i]['order_number'].'"');
            $Max_source_code = 0;
            for($o=0;$o<count($source);$o++){
                $Max_source_code += (int)$source[$o]['source_code_number']+1;
            }
            $order[$i]['source_code_number'] = $Max_source_code;
            $Max_code_number = $li_source['order_key_number'];
            if ($Max_code_number == null) {
                $Max_code_number = 0;
            }
            $order[$i]['order_code_number'] = $Max_code_number;
        }
        // var_dump($order);
        // exit;
        $total = $this->WeDb->totalView('order', $where);
        return ResultVo::success(['list' => $order, 'total' => $total]);
    }
    // 溯源列表查询
    public function source_list()
    {
        $data = $this->request->param('');
        // var_dump($data);
        // exit;
        $userid = $this->adminInfo['id'];
        $user = $this->WeDb->find('user', "id = {$userid}");
        $field = "*";
        // var_dump($order);
        // exit;
        $where = '';
        if(isset($data['value'])){
            if($data['value']==1){
                $search[3] = 'enter_user_id is null and out_user_id is null';
            }else if($data['value']==2){
                $search[3] = 'enter_user_id is not null and out_user_id is null';
            }else if($data['value']==3){
                $search[3] = 'enter_user_id is not null and out_user_id is not null';
            }
        }
        $search[0] = !empty($userid) ? 'business_id = ' . $user['business_notice'] : '';
        $search[1] = !empty($data['source_number'])?'source_code like "%'.trim($data['source_number']).'%"':'1=1';
        $search[2] = !empty($data['menu_id'])?'menu_id = '.$data['menu_id']:'';
        if($user['role_id'] == 1){
            $search[0] = '';
        }
        foreach ($search as $key => $value) {
            # code...
            if ($value) {
                $where = $where . $value . ' and ';
            }
        }
        $where = substr($where, 0, strlen($where) - 5);
        $source = $this->WeDb->selectView('source', $where, $field, $data['page'], $data['limit'], 'id desc');
        $total = $this->WeDb->totalView('source', $where, 'id');
        for ($i = 0; $i < count($source); $i++) {
            if ($source[$i]['enter_user_id'] == null) {
                $source[$i]['state'] = '未入库';
            } else if ($source[$i]['out_user_id'] == null) {
                $source[$i]['state'] = '已入库';
            } else if ($source[$i]['out_user_id'] != null) {
                $source[$i]['state'] = '已出库';
            }
            if ($source[$i]['source_number'] == null) {
                $source[$i]['source_number'] = 0;
            }
            if($source[$i]['enter_user_id'] != null){
                $user = $this->WeDb->find('user', "id = {$source[$i]['enter_user_id']}");
                $source[$i]['enter_user'] = $user['username'];
            }
            if($source[$i]['out_user_id'] != null){
                $user = $this->WeDb->find('user', "id = {$source[$i]['out_user_id']}");
                $source[$i]['out_user'] = $user['username'];
            }
            $business = $this->WeDb->find('business',"id = {$source[$i]['business_id']}");
            $source[$i]['business']=$business;
        }
        
        return ResultVo::success(['list' => $source, 'total' => $total]);
    }
    // 新建一个批次
    public function orderAdd()
    {
        // 新建订单（一次建立多个溯源码）
        $data = $this->request->param('');
        $sourceANDnumber = [];
        // var_dump($data['data']);
        // exit;
        for($i=0;$i<count($data['data']);$i++){
            $sourceANDnumber[$i] = json_decode($data['data'][$i],true);
        }
        $userid = $this->adminInfo['id'];
        $user = $this->WeDb->find('user', "id= {$userid}");
        // if(empty($data['business_id'])){
        //     $business_id = $data['business_id'];
        // }else{
        //     $business_id = $user['business_notice'];
        // }
        $business_id = !empty($data['business_id'])?$data['business_id']:$user['business_notice'];
        $business = $this->WeDb->find('business','id = '.$business_id);
        if($business['verify_if'] == 3){
            return ResultVo::error(1213,'此商家正在核审中，请联系管理员核审并稍后再试....');
        }
        if(empty($business['business_images'])){
            return ResultVo::error(1213,'商家信息不完善，请填写更为详细的商家信息');
        }else if(empty($business['business_introduction'])){
            return ResultVo::error(1213,'商家信息不完善，请填写更为详细的商家信息');
        }
        
        // $sourceANDnumber = $this->request->param("sourceANDnumber");
        // $order_number = round_Code();
        $order_number = $this->random_batch();
        // var_dump(json_encode($sourceANDnumbe));
        // exit;
        $data = [
            'user_id' => $userid,
            'business_id' => $business_id,
            'order_number' => $order_number,
            'create_time' => date('Y-m-d H:i:s'),
            'source_injson' => json_encode($sourceANDnumber),
        ];
        $orderid = $this->WeDb->insertGetId('order', $data);
        // var_dump(count($sourceANDnumber));
        // exit;
        $source_code_array = array();
        $total=0;
        for ($i = 0; $i < count($sourceANDnumber); $i++) {  //第一维箱种类
            $menu_id = $sourceANDnumber[$i]['menu_id'];
            $number = $sourceANDnumber[$i]['number']; //多少箱
            $menu_number = $sourceANDnumber[$i]['menu_number']; //一箱有多少个
            $menu = $this->WeDb->find('menu', "id = {$menu_id}");
            $menu_certificate = $this->WeDb->find('menu_certificate', "menu_id={$menu_id}");
            $menu_monitor = $this->WeDb->find('menu_monitor', "menu_id={$menu_id}");
            $sourceinsert = array();
            for ($o = 0; $o < $number; $o++) {  //第二维多少箱
                $source_code = "SY-".self::code_round($o+1);
                $total += 1;
                $in_data = [
                    'order_id' => $orderid,
                    'business_id' => $business_id,
                    'menu_id' => $menu_id,
                    'order_number' => $order_number,
                    'menu_name' => $menu['menu_name'],
                    'menu_address' => $menu['menu_address'],
                    'menu_weight' => $menu['menu_weight'],
                    'production_time' => $menu['production_time'],
                    'quality_time' => $menu['quality_time'],
                    'menu_images_json' => $menu['menu_images_json'],
                    'monitor_image' => $menu_monitor['monitor_image'],
                    'test_location' => $menu_monitor['test_location'],
                    'sample_name' => $menu_monitor['sample_name'],
                    'monitoring_time' => $menu_monitor['monitoring_time'],
                    'source_code' => $source_code,
                    'certificate_image' => $menu_certificate['certificate_image'],
                    'source_code_number' => $menu_number,
                ];
                // tp队列生成溯源码
                $jobHandlerClassName  = 'app\wap\controller\job\order';
                $jobQueueName = "createorder";
                $num = 50000;
                $this->redis = new Redis();
                $this->redis->set('has_create', $num);
                $this->redis->set('gotostatus', 1);
                $isPushed = Queue::push($jobHandlerClassName, $in_data, $jobQueueName);
                if ($isPushed !== false) {
                    for ($n = 0; $n <= $menu_number; $n++) { //第三维：多少个水果多少个溯源码，因箱子外也有一个码所以是 <=
                        $source_code_array[$i][$o][$n] = $source_code; // $i为箱 $o为多少箱 $n为箱中有多少个 溯源码数量= 水果个数 + 外箱子 = $n+1↑
                    }
                }
                // *** //
            }
        }
        $data = [
            'order_number'=>$order_number,
            'total'=>$total,
        ];
        return ResultVo::success($data);
    }
    public function code_round($index,$length = 6){
        $codelist = Db::name('source')->order('id desc')->select();
        if($codelist){
            $maxcode = $codelist[0]['source_code'];
            $expload = explode("-",$maxcode);
            $number = hexdec($expload[1]);
            $tsnumber = $number+$index;
            $tsnumber = sprintf('%06X',$tsnumber);
            return $tsnumber;
        }else{
            $expload = 000000;
            $number = hexdec($expload);
            $tsnumber = $number+$index;
            $tsnumber = sprintf('%06X',$tsnumber);
            return $tsnumber;
        }
        
    }
    // 溯源二维码生成/批次
    public function scode_list(){
        $data = $this->request->param('');
        $order_number = $data['order_number'];
        $total = 0;
        $source = $this->WeDb->selectView('source','order_number = "'.$order_number.'"');
        $source_total = count($source);
        if(isset($data['total'])){
            $total = $data['total'] - $source_total;
            if($total > 0 ){
                return ResultVo::error('963',$total);
            }
        }else{
            $total = 0;
        }
        if($order_number == null){
            return ResultVo::error(ErrorCode::DATA_NOT['code'], ErrorCode::DATA_NOT['message']);
        }
        $code = $this->WeDb->selectView('source','order_number = "'.$order_number.'"');
        if($code){
            $codeMax = [];
            for($i=0;$i<count($code);$i++){
                $codeMax[$i]['source_code'] = $code[$i]['source_code'];
                $codeMax[$i]['source_number'] = $code[$i]['source_code_number']+1;
            }
            // var_dump($codeMax);
            // exit;
            return ResultVo::success($codeMax);
        }else{
            return ResultVo::error('555','输入的订单号错误');
        }
    }
    // 批次订单内容
    public function order_demo(){
        $data = $this->request->param('');
        $order_number = $data['order_number'];
        $order = $this->WeDb->find('order','order_number =  "'.$order_number.'"');
        $json = json_decode($order['source_injson'],true);
        for($i=0;$i<count($json);$i++){
            $menu_id = $json[$i]['menu_id'];
            $menu = $this->WeDb->find('menu',"id = {$menu_id}");
            $json[$i]['menu_id'] = $menu['menu_name'];
            $json[$i]['menu_weight'] = $menu['menu_weight'];
            //溯源信息-source
            $sourceInfo = $this->WeDb->find('source','order_number = "'.$order_number.'" and menu_id = '.$menu_id);
            $json[$i]['source'] = $sourceInfo;
        }
        return ResultVo::success($json);
    }
    // 删除订单及溯源码
    public function orderdelete(){
        $data = $this->request->param('');
        // var_dump($data);
        // exit;
        $user = $this->WeDb->find('user',"id = {$this->adminInfo['id']}");
        if($user['role_id'] != 1 && $user['role_id'] != 2){
            return ResultVo::error(300,'您的权限不足，请联系负责人或管理员完成此操作');
        }
        $order_id = $data['order_id'];
        $order = $this->WeDb->find('order',"id = {$order_id}");
        $order_number = $order['order_number'];
        $order = db::name('order')->where('order_number = "'.$order_number.'"')->delete();
        $source = db::name('source')->where('order_number = "'.$order_number.'"')->delete();
        return ResultVo::success(['order' => $order , 'source' => $source]);
    }
    // 发货统计详情
    public function source_list_where(){
        $data = $this->request->param('');
        // var_dump($data);
        // exit;
        $userid = $this->adminInfo['id'];
        $user = $this->WeDb->find('user', "id = {$userid}");
        $field = "*";
        // var_dump($order);
        // exit;
        $where = '';
        if(isset($data['value'])){
            if($data['value']==1){
                $search[3] = 'enter_user_id is null and out_user_id is null';
            }else if($data['value']==2){
                $search[3] = 'enter_user_id is not null and out_user_id is null';
            }else if($data['value']==3){
                $search[3] = 'enter_user_id is not null and out_user_id is not null';
            }
        }
        $search[0] = !empty($userid) ? 'business_id = ' . $user['business_notice'] : '';
        $search[1] = !empty($data['source_number'])?'source_code like "%'.trim($data['source_number']).'%"':'1=1';
        $search[2] = !empty($data['menu_id'])?'menu_id = '.$data['menu_id']:'';
        $search[3] = 'goto_user is not null';
        if($user['role_id'] == 1){
            $search[0] = '';
        }
        foreach ($search as $key => $value) {
            # code...
            if ($value) {
                $where = $where . $value . ' and ';
            }
        }
        $where = substr($where, 0, strlen($where) - 5);
        $source = $this->WeDb->selectView('source', $where, $field, $data['page'], $data['limit'], 'id desc');
        $total = $this->WeDb->totalView('source', $where, 'id');
        for ($i = 0; $i < count($source); $i++) {
            if ($source[$i]['enter_user_id'] == null) {
                $source[$i]['state'] = '未入库';
            } else if ($source[$i]['out_user_id'] == null) {
                $source[$i]['state'] = '已入库';
            } else if ($source[$i]['out_user_id'] != null) {
                $source[$i]['state'] = '已出库';
            }
            if ($source[$i]['source_number'] == null) {
                $source[$i]['source_number'] = 0;
            }
            if($source[$i]['enter_user_id'] != null){
                $user = $this->WeDb->find('user', "id = {$source[$i]['enter_user_id']}");
                $source[$i]['enter_user'] = $user['username'];
            }
            if($source[$i]['out_user_id'] != null){
                $user = $this->WeDb->find('user', "id = {$source[$i]['out_user_id']}");
                if($user){
                    $source[$i]['out_user'] = $user['username'];
                }else{
                    $source[$i]['out_user'] = $source[$i]['operator_name'];
                }
            }
            $business = $this->WeDb->find('business',"id = {$source[$i]['business_id']}");
            $source[$i]['business']=$business;
        }
        return ResultVo::success(['list' => $source, 'total' => $total]);
    }
    /*修改快递信息 */
    public function order_update(){
        $data = $this->request->param('');
        // var_dump($data);
        $update = $this->WeDb->update('source','id = '.$data['id'],[
            'goto_user'=>$data['goto_user'],
            'goto_order'=>$data['goto_order'],
            'goto_mobile'=>$data['goto_mobile']
        ]);
        return ResultVo::success($update);
    }
    public function urlGo(){
        $data = $this->request->param('');
        // var_dump($data);
        $code = $data['code'];
        header('Location:'.'http://sy.zsicp.com/h5/#/pages/traceability/traceability?source_code='.$code);
    }
    /**
     * 修改批次内溯源信息
     * @LatTime:2021年5月24日13:45:02 
     */
    public function sourceInfo_update(){
        //修改后台批次内的商品的溯源信息
        $request_data = $this->request->param('');
        
        $where = 'order_number = "'.$request_data['order_number'].'" and menu_id = '.$request_data['menu_id'];
        $result = $this->WeDb->update('source',$where,[
            'menu_name'=>$request_data['menu_name'],
            'menu_address'=>$request_data['menu_address'],
            'production_time'=>$request_data['production_time'],
            'quality_time'=>$request_data['quality_time'],
        ]);
        
        return ResultVo::success($result);
    }
}

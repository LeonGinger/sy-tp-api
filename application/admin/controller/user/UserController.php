<?php

namespace app\admin\controller\user;

use app\admin\controller\BaseCheckUser;
use app\admin\controller\Base;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use app\model\User;
use redis\Redis;
/**
 * 员工相关
 * 员工用户相关(该模块仅用于员工相关的用户操作,不涉及员工操作的方法放置auth相关模块下)
 * 
 */
class UserController extends BaseCheckUser
{
	private $tables = 'user';
    /**
     * [index 员工列表]
     * @HtttpRequest                               get|post
     * @Author       GNLEON
     * @Param
     * @DateTime     2021-03-31T14:36:36+0800
     * @LastTime     2021-03-31T14:36:36+0800
     * @return       [type]                        [description]
     */
    public function index(){

        $data = $this->request->param('');
        $user = User::where("id = {$data["ADMIN_ID"]}")->find();
        if($data['business_notice'] == ""){
            $data['business_notice'] = $user['business_notice'];
        }
        $data['role_id'] = 3;
        if($this->adminInfo['role_id']!='1' && empty($data['business_notice'])){return ResultVo::error(ErrorCode::USER_NOT_LIMIT);}
        $where = '';

        $search[0] = !empty($data['business_notice'])?'business_notice = '.$data['business_notice']:'';
        $search[1] = !empty($data['role_id'])?'role_id in ('.$data['role_id'].')':'';
        $search[2] = !empty($data['phone'])?'phone = '.trim($data['phone']):'';
        $search[3] = !empty($data['username'])?'username like "%'.trim($data['username']).'%"':'';
        
        $search[4] = !empty($data['business_name'])?'business_name like "%'.trim($data['business_name']).'%"':'1=1';
        // $search[4] = !empty($data['stte'])
        //$redis = new Redis;
        //$redis->set('sy_zsicp_com_user_index_search_hasBusiness_0',$search_other[0],3);
        foreach ($search as $key => $value) {
            # code...
            if($value){
                $where=$where.$value.' and ';
            }
        }
        $where=substr($where,0,strlen($where)-5);
        $field ="id,username,gender,phone,user_image,open_id,role_id,delete_time,real_name_state,business_notice,subscribe,create_time,last_login_ip,last_login_time,status,role_name,business_name";
        //分页问题-可能不正确-用下面的list
        if($data['page']==1){$data['page']=0;}
        if($data['page']>1){$data['page'] = $data['page']-1;}

        $list = $this->WeDb->selectView('view_user',$where,$field,$data['page'],$data['size'],'create_time desc');
        $business = $this->WeDb->find('business',"id = {$data['business_notice']}");
        $total = $this->WeDb->totalView('view_user',$where,'id');


// var_dump($Admin_user);
// exit();
        // $list = db('user')->alias('u')
        //         ->join('business b','u.business_notice = b.id','left')
        //         ->join('role r','u.business_notice = b.id','left')
        //         ->field('b.id as bid,u.*')
        //         ->where($where)
        //         ->page($data['page'],$data['size'])
        //         ->select();

        // $list = User::alias('u')
                //->with(['getRole'])
                
                //->join('business b','u.business_notice = b.id','left')
                // ->join('left join business on user.business_notice = business.id ')
                // ->field(
                //     'u.*'
                    // 'u.id',
                    // 'u.username'
                    //'b.id as bid'
                    // 'u.password',
                    // 'u.gender',
                    // 'u.phone',
                    // 'u.user_image',
                    // 'u.open_id',
                    // 'u.role_id',
                    // 'u.delete_time',
                    // 'u.real_name_state',
                    // 'u.business_notice',
                    // 'u.subscribe',
                    // 'u.create_time',
                    // 'u.last_login_ip',
                    // 'u.last_login_time',
                    // 'u.status',
                    // 'b.business_name'
                // )
               
        //     // $list = User::with(['getRole','hasBusiness'=>function($query){
        //     //      $redis = new Redis;
        //     //      $search = $redis->get('sy_zsicp_com_user_index_search_hasBusiness_0')?:"1=1";
        //     //      if($search!="1=1"){
        //     //         $res = $query->where($search);
        //     //         if($res==NULL){
        //     //             return;
        //     //         }
    
        //     //      }
        //     // }])
           // ->where($where)
           // ->page($data['page'],$data['size'])
           // ->select()
           // ->toarray();

        // $total = User::with(['getRole','hasBusiness'])
        //               ->leftjoin('business','user.business_notice = business.id')
        //    ->where($where)
        //    ->count();
        /*可能总数不对-待修*/
        //$total =  $this->WeDb->totalView($this->tables,$where);
        return ResultVo::success(['list'=>$list,'total'=>$total,'business'=>$business]);

    }

    /**
     * [del 删除员工]
     * @HtttpRequest                               post
     * @Author       GNLEON
     * @Param
     * @DateTime     2021-04-01T11:08:50+0800
     * @LastTime     2021-04-01T11:08:50+0800
     * @return       [type]                        [description]
     * @remark       移除权限恢复普通消费者
     */
    public function del(){
        parent::initialize();
        $data= $this->request->param('');
        // var_dump($data);
        // exit;
        // $result = $this->WeDb->update($this->tables,'id = '.$data['id'],['role_id'=>4]);
        // return ResultVo::success();
        $userid = $data['ADMIN_ID'];
        $out_id = $data['out_id'];
        $user = $this->WeDb->find('user', "id = {$userid}");
        $out_user = $this->WeDb->find('user', "id = {$out_id}");
        $business = $this->WeDb->find('business', "id = {$user['business_notice']}");
        $time = date('Y-m-d h:i:s');
        if ($user['role_id'] != 2 && $user['role_id'] != 1) {
            return ResultVo::error(ErrorCode::IS_NOT_BUSINESS['code'], ErrorCode::IS_NOT_BUSINESS['message']);
        }
        $out_data = [
            'role_id' => 4,
            'business_notice' => ''
        ];
        $update = $this->WeDb->update('user', "id = {$out_id}", $out_data);
        // 推送模板消息
        // 推送给操作员↓
        $da_content = [
            'content1' => ['value' => '您已被移出公司', 'color' => "#000000"],
            'content2' => ['value' => "公司名称：{$business['business_name']}", 'color' => "#000000"],
            'content3' => ['value' => "移出时间：{$time}", 'color' => "#000000"],
            'content4' => ['value' => '你的账号已更改为消费者', 'color' => "#000000"],
            'remark' => ['value' => '感谢您对公司作出的贡献', 'color' => "#000000"],
        ];
        $data = [
            'Template_id' => 'ctssIEGGg1132D-Xt8t0CJ1d4RWCLtKj5iO8lcAzeP4',
            'openid' => $out_user['open_id'],
            'url' => 'https://sy.zsicp.com/h5/#/pages/my/my',
            'content' => $da_content,
        ];
        $return = $this->Wechat_tool->sendMessage($data);
        // 推送给商家↓
        $bs_content = [
            'content1' => ['value' => "{$out_user['username']} 已被你移出公司", 'color' => "#000000"],
            'content2' => ['value' => "公司名称：{$business['business_name']}", 'color' => "#000000"],
            'content3' => ['value' => "移出时间：{$time}", 'color' => "#000000"],
            'content4' => ['value' => "{$out_user['username']}的账号已进行变更", 'color' => "#000000"],
            'remark' => ['value' => '操作成功', 'color' => "#000000"],
        ];
        $data = [
            'Template_id' => 'ctssIEGGg1132D-Xt8t0CJ1d4RWCLtKj5iO8lcAzeP4',
            'openid' => $user['open_id'],
            'url' => 'https://sy.zsicp.com/h5/#/pages/employee/employee-list',
            'content' => $bs_content,
        ];
        $return = $this->Wechat_tool->sendMessage($data);
        //*//
        return ResultVo::success($update);
    }
    /**
     * [edit 修改员工信息]
     * @HtttpRequest                               get|post
     * @Author       GNLEON
     * @Param
     * @DateTime     2021-04-01T14:15:04+0800
     * @LastTime     2021-04-01T14:15:04+0800
     * @return       [type]                        [description]
     * @remark   2021年4月1日14:29:15  差个头像
     */
    public function edit(){
        $data = $this->request->param('');
        $data['id'] = $data['ADMIN_ID'];
        if (empty($data['id']) || empty($data['username'])){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        $id = $data['id'];
        $username = strip_tags($data['username']);
        $phone = strip_tags($data['phone']);
        $auth_admin = User::where('id',$id)
            ->field('id,username')
            ->find();
        if (!$auth_admin){
            return ResultVo::error(ErrorCode::DATA_NOT, "员工不存在");
        }
        $login_info = $this->adminInfo;
        $login_user_name = isset($login_info['username']) ? $login_info['username'] : '';
        // 如果是超级管理员，判断当前登录用户是否匹配
        if ($auth_admin->username == 'admin' && $login_user_name != $auth_admin->username){
            return ResultVo::error(ErrorCode::DATA_NOT, "最高权限用户，无权修改");
        }
        /*开始员工修改信息*/
        $info = User::where('phone',$phone)
            ->where('id !='.$id)
            ->field('id')
            ->find();
        // 判断phone 是否重名，剔除自己
        if (!empty($info['id']) && $info['id'] != $id){
            return ResultVo::error(ErrorCode::DATA_REPEAT, "员工已存在");
        }

        $status = isset($data['status']) ? $data['status'] : 0;
        $role_id = isset($data['role_id']) ? $data['role_id'] : $info->role_id;
        $auth_admin->username = $username;
        $auth_admin->phone = $phone;
        $auth_admin->role_id = $role_id;
        $result = $auth_admin->save();
        if(!$result){
            return ResultVo::error(ErrorCode::DATA_CHANGE);

        }
        return ResultVo::success();

    }}  

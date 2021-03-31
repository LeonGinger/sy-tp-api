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
 * 员工用户相关(该模块仅用于员工相关的用户操作,不涉及员工操作的方法放置auth模块下)
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
        if($this->adminInfo['role_id']!='1' && empty($data['business_notice'])){return ResultVo::error(ErrorCode::USER_NOT_LIMIT);}
        $where = '';

        $search[0] = !empty($data['business_notice'])?'business_notice = '.$data['business_notice']:'';
        $search[1] = !empty($data['role_id'])?'role_id = '.$data['role_id']:'';
        $search[2] = !empty($data['phone'])?'phone = '.trim($data['phone']):'';
        $search[3] = !empty($data['username'])?'username like "%'.trim($data['username']).'%"':'';
        
        $search[4] = !empty($data['business_name'])?'business_name like "%'.trim($data['business_name']).'%"':'1=1';
        
        foreach ($search as $key => $value) {
            # code...
            if($value){
                $where=$where.$value.' and ';
            }
        }
        $where=substr($where,0,strlen($where)-5);

        $list = User::with(['getRole','hasBusiness'])
         // ->field(
         //            'u.id',
         //            'u.username'
         //            // 'u.password',
         //            // 'u.gender',
         //            // 'u.phone',
         //            // 'u.user_image',
         //            // 'u.open_id',
         //            // 'u.role_id',
         //            // 'u.delete_time',
         //            // 'u.real_name_state',
         //            // 'u.business_notice',
         //            // 'u.subscribe',
         //            // 'u.create_time',
         //            // 'u.last_login_ip',
         //            // 'u.last_login_time',
         //            // 'u.status',
         //            // 'b.business_name'
         //        )
                ->alias('u')
                ->leftjoin('business b','u.business_notice = b.id')
               
            // $list = User::with(['getRole','hasBusiness'=>function($query){
            //      $redis = new Redis;
            //      $search = $redis->get('sy_zsicp_com_user_index_search_hasBusiness_0')?:"1=1";
            //      if($search!="1=1"){
            //         $res = $query->where($search)->find();
            //         if($res==NULL){
            //             return;
            //         }
    
            //      }
            // }])
           ->where($where)
           ->page($data['page'],$data['size'])
           ->select()
           ->toarray();

        $total = User::with(['getRole','hasBusiness'])
                      ->leftjoin('business','user.business_notice = business.id')
           ->where($where)
           ->count();
        /*可能总数不对-待修*/
        //$total =  $this->WeDb->totalView($this->tables,$where);
        return ResultVo::success(['list'=>$list,'total'=>$total]);

    }
}	
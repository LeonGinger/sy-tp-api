<?php

namespace app\wap\controller\auth;

use app\wap\controller\Base;
use app\common\enums\ErrorCode;
use app\common\model\auth\AuthAdmin;
use app\common\model\auth\AuthPermission;
use app\common\model\auth\AuthPermissionRule;
use app\common\model\auth\AuthRoleAdmin;
use app\common\utils\PassWordUtils;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;
use think\facade\Hook;

/**
 * 登录
 */

class LoginController extends Base
{
    /**
     * 获取用户信息
     */
    public function index()
    {


        if (!request()->isPost()){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }

        $user_name = request()->post('userName');
        $pwd = request()->post('pwd');
        if (!$user_name || !$pwd){
            return ResultVo::error(ErrorCode::VALIDATION_FAILED, "username 不能为空。 password 不能为空。");
        }
        $admin = AuthAdmin::where('username',$user_name)
            ->field('id,username,avatar,password,status')
            ->find();

        if (empty($admin) ||  PassWordUtils::create($pwd) != $admin->password){
            return ResultVo::error(ErrorCode::USER_AUTH_FAIL);
        }
        if ($admin->status != 1){
            return ResultVo::error(ErrorCode::USER_NOT_PERMISSION);
        }

        $info = $admin->toArray();

        unset($info['password']);

        // 保存用户信息
        $loginInfo = AuthAdmin::loginInfo($info['id'],$info);
        $admin->last_login_ip = request()->ip();
        $admin->last_login_time = date("Y-m-d H:i:s");
        $admin->save();

        $res = [];
        $res['id'] = !empty($loginInfo['id']) ? intval($loginInfo['id']) : 0;
        $res['token'] = !empty($loginInfo['token']) ? $loginInfo['token'] : '';

        return ResultVo::success($res);
    }

    /**
     * 获取登录用户信息
     */
    public function userInfo()
    {

        $res = Hook::exec('app\\admin\\behavior\\CheckAuth', []);
        if (empty($res["id"])) {
            return ResultVo::error(ErrorCode::LOGIN_FAILED);
        }
        $res['id'] = !empty($res['id']) ? intval($res['id']) : 0;
        $res['avatar'] = !empty($res['avatar']) ? PublicFileUtils::createUploadUrl($res['avatar']) : '';

        $authRules = [];
        if (!empty($res["username"]) && $res["username"] == "admin") {
            $authRules = ['admin'];
        } else {
            // 获取权限列表
            $role_ids = AuthRoleAdmin::where('admin_id', $res['id'])->column('role_id');
            if ($role_ids){
                $permission_rules = AuthPermission::where('role_id','in',$role_ids)
                    ->field(['permission_rule_id'])
                    ->select();
                $permission_rule_ids = [];
                foreach ($permission_rules as $v) {
                    $permission_rule_ids[] = $v['permission_rule_id'];
                }
                $permission_rule_ids = array_unique($permission_rule_ids);
                $rules = AuthPermissionRule::where('id', "in", $permission_rule_ids)->field("name")->select();
                $authRules = $rules ? array_column($rules->toArray(), "name") : [];
            }
        }
        $res['authRules'] = $authRules;
        return ResultVo::success($res);
    }

    /**
     * 退出
     */
    public function out()
    {
        if (!request()->isPost()){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }

        $id = request()->get('ADMIN_ID');
        $token = request()->get('ADMIN_TOKEN');
        if (!$id || !$token) {
            return ResultVo::error(ErrorCode::LOGIN_FAILED);
        }
        $loginInfo = AuthAdmin::loginInfo($id,(string)$token);
        if ($loginInfo == false){
            return ResultVo::error(ErrorCode::LOGIN_FAILED);
        }

        AuthAdmin::loginOut($id);

        return ResultVo::success();

    }


    /**
     * 修改密码
     */
    public function password(){
        if (!request()->isPost()){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $id = request()->get('ADMIN_ID');
        $token = request()->get('ADMIN_TOKEN');
        if (!$id || !$token) {
            return ResultVo::error(ErrorCode::LOGIN_FAILED);
        }
        $loginInfo = AuthAdmin::loginInfo($id,(string)$token);
        if ($loginInfo == false){
            return ResultVo::error(ErrorCode::LOGIN_FAILED);
        }
        $old_password = request()->post('old_password');
        $new_password = request()->post('new_password');

        $admin_info = AuthAdmin::where('id',$id)->field('username,password')->find();
        if ($admin_info['password'] != PassWordUtils::create($old_password)){
            return ResultVo::error(ErrorCode::USER_AUTH_FAIL, "原始密码错误");
        }

        if ($admin_info['password'] == PassWordUtils::create($new_password)){
            return ResultVo::error(ErrorCode::USER_AUTH_FAIL, "密码未做修改");
        }

        $admin_info->password = PassWordUtils::create($new_password);
        if (!$admin_info->save()){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        return ResultVo::success();

    }
}

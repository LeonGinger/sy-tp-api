<?php
/**
 * Created by PhpStorm.
 * User: Host-0034
 * Date: 2018/5/30
 * Time: 18:48
 */

namespace app\admin\behavior;

use app\common\exception\JsonException;
use app\common\enums\ErrorCode;
use app\common\model\auth\AuthAdmin;
use app\common\model\auth\AuthPermission;
use app\common\model\auth\AuthPermissionRule;
use app\common\model\auth\AuthRoleAdmin;
use think\Request;

/**
 * 登录验证
 * Class CheckAuth
 * @package app\admin\behavior
 */
class CheckAuth
{

    public function run(Request $request, $params)
    {
        // 行为逻辑
        $id = request()->header('admin-id');
        $token = request()->header('admin-token');

        if (!$id || !$token) {
            throw new JsonException(ErrorCode::LOGIN_FAILED);
        }

        $loginInfo = AuthAdmin::loginInfo($id, (string)$token);
        if ($loginInfo == false) {
            throw new JsonException(ErrorCode::LOGIN_FAILED);
        }
        // 排除权限
        // 由于封装的自定义路由入口 导致此处下面获取的rule_name 只获取了入口 有待改进
        $not_check = ["admin/auth.login/userinfo","admin/entrance/index"];

        // 获取权限列表
        $authRules = [];
        $role_ids = AuthRoleAdmin::where('admin_id', $id)->column('role_id');
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

        //检查权限
        $module     = request()->module();
        $controller = parse_name(request()->controller());
        $action     = request()->action();
        $rule_name = strtolower($module . '/' . $controller . '/' . $action);
        // 不在排除的权限内，并且 用户不为超级管理员
        if (!in_array(strtolower($rule_name), $not_check) && (empty($loginInfo['username']) || $loginInfo['username'] != 'admin')) {
            $auth_rule_names = $authRules;
            if (!self::check($loginInfo, $auth_rule_names, [$rule_name],'and')){
                throw new JsonException(ErrorCode::AUTH_FAILED);
            }
        }
        return $loginInfo;
    }

    /**
     * 检查权限
     * @param array $admin 管理员信息
     * @param  array $auth_rule_names 管理员id
     * @param array  $name 需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param string $relation 如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @return bool 通过验证返回true;失败返回false
     */
    private static function check($admin, $auth_rule_names = [], $name = [], $relation = 'or'){

        if (empty($auth_rule_names) || empty($name)){
            return false;
        }

        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array($name);
            }
        }
        $auth_permission_rule_list = AuthPermissionRule::where('name','in',$auth_rule_names)
            ->field('id,name,condition')
            ->select();
        $list = [];
        foreach ($auth_permission_rule_list as $rule){
            if (!empty($rule['condition'])) { //根据condition进行验证
                $admin = $admin; // $admin 不能删除，下面正则会用到
                $command = preg_replace('/\{(\w*?)\}/', '$admin[\'\\1\']', $rule['condition']);
                //dump($command);//debug
                @(eval('$condition=(' . $command . ');'));
                if ($condition) {
                    $list[] = strtolower($rule['name']);
                }
            }else{
                $list[] = strtolower($rule['name']);
            }
        }

        if ($relation == 'or' and !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ($relation == 'and' and empty($diff)) {
            return true;
        }

        return false;

    }

}
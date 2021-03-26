<?php


namespace app\admin\controller;

use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use \think\Request;
use app\common\enums\RouteNo;
class EntranceController
{
    public function __construct()
    {
        
    }
    // public function index()
    // {
    //     return ResultVo::error(ErrorCode::NOT_NETWORK);
    // }
    public function index(Request $request){

        $request_data=$request->param('');
        if(array_key_exists($request->path(),RouteNo::NOT_WEBAPI)){
            $methodType = RouteNo::NOT_WEBAPI[$request->path()];

        }else{
            //$methodType = $this->getMethod($request_data['methods'],$request->method());
            $methodType = $this->getMethod($request_data['methods']);
        }

        //$methodType = $this->getMethod($request_data['methods']);
        $api = $this->namespacePrefix.$methodType;


        $action=action($api);

        return $action;
    }

    /*@autho GNLEON
      @remark 坑-window和linux斜杆
    */
    public function getMethod($method){
        $find_method = $this->methodApi[$method];
        return $find_method;
    }
    private $namespacePrefix='admin';
    private $methodApi = [
        'test'=>'/auth/LoginController/index',
        'codestatus'=>'/source/SourcecodeController/createcodestatus',

        /*登出登录、用户信息 */
        'login'=>'/auth/LoginController/index',
        'out'=>'/auth/LoginController/out',
        'userInfo'=>'/auth/LoginController/userInfo',
        'password'=>'/auth/LoginController/password',
        'code'=>'/source/SourcecodeController/createcode',

        /*用户管理 */
        'admin_list'=>'/auth/AdminController/index',
        'admin_del'=>'/auth/AdminController/delete',
        'admin_add'=>'/auth/AdminController/save',
        'admin_update'=>'/auth/AdminController/edit',
        
        /*角色管理 */
        'role_list'=>'/auth/RoleController/index',
        'role_add'=>'/auth/RoleController/save',
        'role_update'=>'/auth/RoleController/edit',
        'role_del'=>'/auth/RoleController/delete',
        'authList'=>'/auth/RoleController/authList',
        'role_auth'=>'/auth/RoleController/auth',
        
        /*权限管理 */
        'permission_list'=>'/auth/PermissionRuleController/index',
        'permission_add'=>'/auth/PermissionRuleController/save',
        'permission_update'=>'/auth/PermissionRuleController/edit',
        'permission_del'=>'/auth/PermissionRuleController/delete',

        /*商家相关*/
        'enterprise_list'=>'/business/BusinessController/index',
        'enterprise_edit'=>'/business/BusinessController/edit',
        'enterprise_state'=>'/business/BusinessController/state',
        

    ];

}

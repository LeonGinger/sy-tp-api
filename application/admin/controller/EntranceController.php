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
        /*资源相关 */
        'image_upload'=>'/file/ResourceController/add',
        'image_list'=>'/file/ResourceController/index',
        'imagetag_list'=>'/file/ResourceTagController/index',
        'imagetag_add'=>'/file/ResourceTagController/add',

        /*面板数据 */
        'index_data'=>'/sys/AdminController/get_index_data',
        'index_echartsdata'=>'/sys/AdminController/echarts_list',
        /*登出登录、用户信息 */
        'login'=>'/auth/LoginController/index',
        'logins'=>'/auth/LoginController/index_scan',
        'out'=>'/auth/LoginController/out',
        'userInfo'=>'/auth/LoginController/userInfo',
        'password'=>'/auth/LoginController/password',
        'code'=>'/source/SourcecodeController/createcode',

        /*用户管理 */
        'admin_list'=>'/auth/AdminController/index',
        'admin_del'=>'/auth/AdminController/delete',
        'admin_add'=>'/auth/AdminController/save',
        'admin_update'=>'/auth/AdminController/edit',
        /*通用-用户列表*/
        'user_list'=>'/user/UserController/index_user',
        'user_ids'=>'/user/UserController/details_user',
        /*员工*/
        'employee_list'=>'/user/UserController/index',
        'employee_del'=>'/user/UserController/del',
        'employee_edit'=>'/user/UserController/edit',
        
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
        'businessAll'=>'/business/BusinessController/businessAll',
        'business_update'=>'/business/BusinessController/business_update',
        
        /*商品相关*/
        'menu_list'=>'/business/MenuController/index',
        'menu_details'=>'/business/MenuController/details',
        'menu_add'=>'/business/MenuController/add',
        'menu_update'=>'/business/MenuController/edit',
        'menu_del'=>'/business/MenuController/del',
        'menu_state'=>'/business/MenuController/state',

        //批次管理
        'orderAdd'=>'/source/SourcecodeController/orderAdd',
        'order_list'=>'/source/SourcecodeController/order_list',
        'source_list'=>'/source/SourcecodeController/source_list',
        'scode_list'=>'/source/SourcecodeController/scode_list',
        'order_demo'=>'/source/SourcecodeController/order_demo',
        'order_delete'=>'/source/SourcecodeController/orderdelete',

        //查询管理
        'sourcelog_index' => '/source/CertifyesultController/index',
        'soucelog_del' => '/source/CertifyesultController/souce_del',
        'sroucelog_echarts' => '/source/CertifyesultController/total_echarts',
        'sroucearea_echarts' => '/source/CertifyesultController/area_echarts',

        //公众号管理
        'fans_list'=>'/wechat/FansController/index',
        'fans_sync'=>'/wechat/FansController/sysfans',
        'fans_syncstate'=>'/wechat/FansController/fans_state',

        //系统管理
        'setting_index'=> '/sys/SettingController/sys_get',
        'sys_save' => '/sys/SettingController/sys_set',
        'pb_index' => '/sys/SettingController/porblem_index',
        'pb_add' => '/sys/SettingController/porblem_add',
        'pb_edit' => '/sys/SettingController/problem_edit',
        'pb_del' => '/sys/SettingController/problem_del',
        'bn_index' => '/sys/SettingController/BusinessNotice_index',
        'bn_edit' => '/sys/SettingController/BusinessNotice_edit',
        /*数据备份*/
        'database_index'=>'/sys/SettingController/index_base',
        'database_dump'=>'/sys/SettingController/dump_base',
        'database_down'=>'/sys/SettingController/down_base',
        'database_del'=>'/sys/SettingController/del_base',
        // 每日统计
        'business_push'=>'/business/BusinessController/business_push',
    ];

}

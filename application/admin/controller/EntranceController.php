<?php


namespace app\admin\controller;

use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use \think\Request;
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
        $methodType = $this->getMethod($request_data['methods']);
        $api = $this->namespacePrefix.$methodType;
//var_dump($api);

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
        'userInfo'=>'/auth/LoginController/userInfo',
        'login'=>'/auth/LoginController/index',
        'out'=>'/auth/LoginController/out',
        'password'=>'/auth/LoginController/password',
        'code'=>'/source/SourcecodeController/createcode',
        'codestatus'=>'/source/SourcecodeController/createcodestatus',
        
        'adminlist'=>'/auth/AdminController/index',
        'rolelist'=>'/auth/AdminController/roleList',
        'admindel'=>'/auth/AdminController/delete',
        
        
        
        
        
            
    ];

}

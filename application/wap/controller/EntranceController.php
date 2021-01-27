<?php


namespace app\wap\controller;

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
    private $namespacePrefix='wap';
    private $methodApi = [
        'userindex'=>'/user/UserController/index',
        'test'=>'/file/UploadController/qiuNiuUpToken',
        // 'test'=>'/auth/LoginController/index',
    ];

}

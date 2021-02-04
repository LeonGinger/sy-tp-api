<?php


namespace app\wap\controller;

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
 
        if(array_key_exists($request->path(),RouteNo::NOT_WAPAPI)){
            $methodType = RouteNo::NOT_WAPAPI[$request->path()];
        }else{
            $methodType = $this->getMethod($request_data['methods']);
        }
        

        $api = $this->namespacePrefix.$methodType;
        

        $action=action($api);

        return $action;
    }

    /*@autho GNLEON
      @remark 坑-window和linux斜杆
    */
    public function getMethod($method,$m_type=''){
        // if($m_type=='no'){
        //     $find_method = $this->methodNoApi[$method];
        // }else{
            $find_method = $this->methodApi[$method];
        // }
       
        return $find_method;
    }
    private $namespacePrefix='wap';
    private $methodApi = [
        'userindex'=>'/user/UserController/index',
        'test'=>'/file/UploadController/qiuNiuUpToken',
        'utoken'=>'/user/UserController/set_token',
        'business_applyad'=>'/business/BusinessController/Apply_add',
        // 'test'=>'/auth/LoginController/index',
    ];
}

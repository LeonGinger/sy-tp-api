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
            $methodType = $this->getMethod($request_data['methods'],$request->method());
        }
        

        $api = $this->namespacePrefix.$methodType;
        

        $action=action($api);

        return $action;
    }

    /*@autho GNLEON
      @remark 坑-window和linux斜杆
    */
    public function getMethod($method,$request_type=''){
        $find_method = $this->methodApi_POST[$method];
        // var_dump($request_type);
        // exit;
        switch ($request_type) {
            case 'POST':
                try{
                    $find_method = $this->methodApi_POST[$method];
                }catch(\Throwable $th){
                    throw new \think\Exception(ErrorCode::HTTP_METHOD_NOT_ALLOWED['message'],ErrorCode::HTTP_METHOD_NOT_MEHOTDS['code']);
                }
                # code...
                break;
            case 'GET':
                try{
                    $find_method = $this->methodApi_GET[$method];
                }catch(\Throwable $th){
                    throw new \think\Exception(ErrorCode::HTTP_METHOD_NOT_MEHOTDS['message'],ErrorCode::HTTP_METHOD_NOT_MEHOTDS['code']);
                }
                # code...
                break;
            default:
                throw new \think\Exception(ErrorCode::HTTP_METHOD_NOT_MEHOTDS['message'],ErrorCode::HTTP_METHOD_NOT_MEHOTDS['code']);
                # code...
                break;
        }
        
        // if($m_type=='no'){
        //     $find_method = $this->methodNoApi[$method];
        // }else{
            //$find_method = $this->methodApi[$method];
        // }
        return $find_method;
    }
    private $namespacePrefix='wap';
    
    private $methodApi_POST = [
        'upload_headimg'=>'/user/UserController/upload_headimg',
        'usersave'=>'/user/UserController/index',
        'userindex'=>'/user/UserController/index',
        'test'=>'/file/UploadController/qiuNiuUpToken',
        'utoken'=>'/user/UserController/set_token',
        'this_user'=>'/user/UserController/this_user',
        // 'test'=>'/auth/LoginController/index',
        'getConfig'=>'/wechat/WechatController/getConfig',
        // Business操作
        'my_user'=>'/business/BusinessController/my_user',
        'my_menu'=>'/business/BusinessController/my_menu',
        'business_applyad'=>'/business/BusinessController/Apply_add',
        'business_selectall'=>'/business/BusinessController/Apply_selectall',
        'business_updateall'=>'/business/BusinessController/Apply_updateall',
        'business_delete'=>'/business/BusinessController/Apply_delete',
        'business_update'=>'/business/BusinessController/Apply_update',
        'out_my_user'=>'/business/BusinessController/out_my_user',
        // Menu操作
        'create_menu'=>'/menu/MenuController/create_menu',
        'delete_menu'=>'/menu/MenuController/delete_menu',
        'update_menu'=>'/menu/MenuController/update_menu',
        // source操作
        'Add_order'=>'/source/SourceController/Add_order',
        
        'open_source'=>'/source/SourceController/open_source', //出入库
        'opend_list'=>'/source/SourceController/opend_list'//出入库记录
    ];
    private $methodApi_GET = [
        //'upload_headimg'=>'/user/UserController/upload_headimg',
        'usersave'=>'/user/UserController/index',
        'business_applyad'=>'/business/BusinessController/Apply_add',
        // 'test'=>'/auth/LoginController/index',
        'join_my'=>'/business/BusinessController/join_my',
    ];



}

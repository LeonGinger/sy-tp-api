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
        
        // var_dump($method,$request_type);
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
        'h5uploadimg'=>'/file/UploadController/Img_Allupload',
        'test'=>'/file/UploadController/qiuNiuUpToken',
        'update_token'=>'/user/UserController/update_token',
        // 'test'=>'/auth/LoginController/index',
        // wx操作
        'getConfig'=>'/wechat/WechatController/getConfig',
        'send_message'=>'/wechat/WechatController/getConfig',
        // UserController操作
        'utoken'=>'/user/UserController/set_token',
        'this_user'=>'/user/UserController/this_user',
        'upload_headimg'=>'/user/UserController/upload_headimg',
        'usersave'=>'/user/UserController/usave',
        'userindex'=>'/user/UserController/index',
        'this_source_log'=>'/user/UserController/this_source_log',
        'iphone_code'=>'/user/UserController/iphone_code',
        'iphonebs_code'=>'/user/UserController/business_sendcode',
        'common_problem'=>'/user/UserController/common_problem',
        'business_notice'=>'/user/UserController/business_notice',
        'subscribeMassage'=>'/user/UserController/subscribeMassage', //订阅消息推送
        // BusinessController操作
        'my_user'=>'/business/BusinessController/my_user',
        'my_menu'=>'/business/BusinessController/my_menu',
        'business_applyad'=>'/business/BusinessController/Apply_add',
        'business_selectall'=>'/business/BusinessController/Apply_selectall',
        'business_updateall'=>'/business/BusinessController/Apply_updateall',
        'business_delete'=>'/business/BusinessController/Apply_delete',
        'business_update'=>'/business/BusinessController/Apply_update',
        'out_my_user'=>'/business/BusinessController/out_my_user',
        'my_quit'=>'/business/BusinessController/my_quit',
        'businessfind'=>'/business/BusinessController/businessfind',
        'business_open'=>'/business/BusinessController/Apply_open',
        // MenuController操作
        'create_menu'=>'/menu/MenuController/create_menu',
        'delete_menu'=>'/menu/MenuController/delete_menu',
        'update_menu'=>'/menu/MenuController/update_menu',
        'find_menu'=>'/menu/MenuController/find_menu',
        'menu_hot'=>'/menu/MenuController/menu_hot',
        // sourceController操作
        'Add_order'=>'/source/SourceController/Add_order',
        'open_source'=>'/source/SourceController/open_source', //出入库
        'opend_list'=>'/source/SourceController/opend_list', //出入库记录
        'SelectAll'=>'/source/SourceController/SelectAll',
        'SelectAllUp'=>'/source/SourceController/SelectAllUp',
        'goto_update'=>'/source/SourceController/goto_update',
        // 每日统计
        'business_push'=>'/business/BusinessController/business_push',
        
    ];
    private $methodApi_GET = [
        //'upload_headimg'=>'/user/UserController/upload_headimg',
        'usersave'=>'/user/UserController/index',
        'business_applyad'=>'/business/BusinessController/Apply_add',
        // 'test'=>'/auth/LoginController/index',
        'join_my'=>'/business/BusinessController/join_my',
    ];



}

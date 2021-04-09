<?php
/**
 * @Atuhor :GNLEON
 * lasttime:2021年1月29日 00:46:45
 */
namespace app\common\enums;

/**
 * 全框架无需验证的接口
 * Class ErrorCode
 * @package app\common\model
 */
class RouteNo
{

    // +----------------------------------------------------------------------
    // | 方法
    // +----------------------------------------------------------------------

    /**
     * 前端不需要token的接口
     */
    const NOT_WAPAPI = [ 
        'wap/user/user/set_token'=>'/user/UserController/set_token',
        'wap/user/user/Login'=>'/user/UserController/Login',
        // 'wap/Add_order'=>'/source/SourceController/Add_order'
        // 'wap/iphone_code'=>'/user/UserController/iphone_code',
        'wap/send_message'=>'/wechat/WechatController/getConfig',
    ];
    /**
     * 后台不需要token的接口
     */
    const NOT_WEBAPI = [ 
        'web/region/get_region'=>'/region/RegionController/get_region',
        'web/auth/send_code'=>'/auth/LoginController/send_code',
        'web/file/uploadfile'=>'/file/UploadController/createFile',
        // 'wap/user/user/set_token',
        // 'web/wechat/index'=>'/wechat/FansController/index',
        // 'web/wechat/sysfans'=>'/wechat/FansController/sysfans',
        
    ];
    // 管理员相关

}

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
        'wap/user/user/testsend'=>'/user/UserController/testsend',
        // 'wap/Add_order'=>'/source/SourceController/Add_order'
        // 'wap/iphone_code'=>'/user/UserController/iphone_code',
        'wap/send_message'=>'/wechat/WechatController/getConfig',
        // 'wap/testLogin'=>'/wechat/WechatController/testLogin',
        'wap/business_push'=>'/business/BusinessController/business_push',
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
        // 'web/wechat/fans_state'=>'/wechat/FansController/fans_state',
        //'web/source/total_echarts'=>'/source/CertifyesultController/total_echarts',
        // 'web/sys/dataindex'=>'/sys/SettingController/index_base',
        // 'web/sys/datadump'=>'/sys/SettingController/dump_base',
        // 'web/sys/datadown'=>'/sys/SettingController/down_base',
        // 'web/sys/dataindex'=>'/sys/SettingController/index_base',
        // 'web/sys/dataindex'=>'/sys/SettingController/index_base',
        'web/setting_indexno'=>'/sys/SettingController/sys_getno',
        
    ];
    // 管理员相关

}

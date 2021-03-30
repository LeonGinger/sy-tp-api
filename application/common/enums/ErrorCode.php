<?php
// +----------------------------------------------------------------------
// | ThinkPHP 5 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 .
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎明晓 <lmxdawn@gmail.com>
// +----------------------------------------------------------------------

namespace app\common\enums;

/**
 * 后台系统错误码
 * Class ErrorCode
 * @package app\common\model
 */
class ErrorCode
{

    // +----------------------------------------------------------------------
    // | 系统级错误码
    // +----------------------------------------------------------------------
    const NOT_NETWORK = [ 'code' => 1, 'message' => '系统繁忙，请稍后再试。'];

    // +----------------------------------------------------------------------
    // | 服务级错误码
    // +----------------------------------------------------------------------
    const LOGIN_FAILED = [ 'code' => 2, 'message' => '登录失效'];
    const HTTP_METHOD_NOT_ALLOWED = [ 'code' => 3, 'message' => '网络请求不予许'];
    const VALIDATION_FAILED = [ 'code' => 4, 'message' => '身份验证失败'];
    const USER_AUTH_FAIL = [ 'code' => 5, 'message' => '用户名或者密码错误'];
    const USER_NOT_PERMISSION = [ 'code' => 6, 'message' => '当前没有权限登录'];
    const AUTH_FAILED = [ 'code' => 7, 'message' => '权限验证失败'];
    const DATA_CHANGE = [ 'code' => 8, 'message' => '数据没有任何更改'];
    const DATA_REPEAT = [ 'code' => 9, 'message' => '数据重复'];
    const DATA_NOT = [ 'code' => 10, 'message' => '数据不存在'];
    const DATA_VALIDATE_FAIL = [ 'code' => 11, 'message' => '数据验证失败'];
    const HTTP_METHOD_NOT_MEHOTDS = [ 'code' => 404, 'message' => '接口丢失了QAQ'];
    const NOT = [ 'code' => 12, 'message' => '操作失败请重试'];
    const NOT_PHONE_CODE = [ 'code' => 33, 'message' => '验证码错误'];
    const DATA_NOT_CONTRNT = ['code'=>44, 'message' =>'数据不完整，请重试' ];
    const USER_NOT_BUSINESS = ['code'=>55,'message' =>'您不是操作员，无需注销'];
    const PHONE_IS_NULL = ['code'=>15,'message' => '手机号码不能为空'];
    const PHONE_IS_TWO = ['code'=>16,'message'=>'手机号码已被注册'];
    const UPLOAD_IS_NULL = ['code'=>23,'message'=>'数据为NULL,请重试'];
    const IS_NOT_BUSINESS = ['code'=>24,'message'=>'抱歉，您不是本商家的负责人'];
    const USER_BUSINESS_TRUE = ['code'=>25,'message'=>'sorry,您同时只能绑定一家企业'];
    const USER_NOT_LIMIT = ['code'=>26,'message'=>'您的权限不足，无法查阅此内容'];
    const USER_ROLE_IN = ['code'=>27,'message'=>'抱歉，您是企业的负责人不能成为企业的操作员'];
    const USER_ROLE_REPEAT = ['code'=>28,'message'=>'您已经是操作员了，请注销后重试'];
    const OUT_LIMIT_NOT = ['code'=>29,'message'=>'sorry,您无权限进行此操作'];
    const BUSINESS_REPEAT = ['code'=>30,'message'=>'sorry,您所注册的公司名已被注册，请重试'];
    // 管理员相关

}

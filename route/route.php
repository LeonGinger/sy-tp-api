<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// return [
//   'sqs'=>'/admin/ad/ad',
//   'login'=>'/admin/auth/Login/',
// ];
use think\facade\Route;
//  Route::rule('sqs','admin/ad/ad','post');
 Route::rule('web/:methods','admin/Entrance/index');
 //Route::rule('wap/:methods','wap/Entrance/index')->middleware('JwtApi')->allowCrossDomain();
 Route::rule('wap/:methods','wap/Entrance/index')->middleware('JwtApi');
 //Route::rule('utoken','/wap/user/user/set_token');

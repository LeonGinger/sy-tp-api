<?php

namespace app\http\middleware;

use app\common\auth\JwtAuthWap;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use think\Request;
use app\common\enums\RouteNo;
class JwtApi 
{
    public function handle(Request $request, \Closure $next)
    {   

        if(array_key_exists($request->path(),RouteNo::NOT_WAPAPI)){
            return $next($request);
        }
 
        $token= $request->param('token')?:$request->header('token');

        if($token){
        
            $jwtAuth =JwtAuthWap::getInstance();

            $jwtAuth->setToken($token);
 
            if($jwtAuth->decode()){

            // if($jwtAuth->validate()&&$jwtAuth->verify()){
                /**
                 * 
                 */
           
                return $next($request);
            }else{
                  return ResultVo::error(ErrorCode::VALIDATION_FAILED);
            }
        }else{
             return  ResultVo::error(ErrorCode::VALIDATION_FAILED);

        }
    }
}   
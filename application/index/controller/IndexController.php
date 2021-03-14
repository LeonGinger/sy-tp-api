<?php
namespace app\index\controller;

use app\common\exception\JsonException;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;

class IndexController
{
    /*此处不做任何业务逻辑代码 */

    /**
     * @LastTime:_
     */
    public function index()
    {
 
        throw new JsonException(ErrorCode::NOT_NETWORK,"Power by ZSICP - ".date('Y-m-d H:i:s',time()));

        @$remark = " Power By ZSICP"
        ."@author:GNLEON"
        ."php >=5.6.0"
        ."topthink/framework 5.1.*"
        ."firebase/php-jwt ^5.2"
        ."naixiaoxin/think-wechat ^1.6"
        ."topthink/think-worker v2.0.9"
        ."workerman/gateway-worker ^3.0"
        ."workerman/workerman ^3.5"
        ."topthink/think-queue 2.0.*"
        .";";

        
    }
// 
//     public function hello($name = 'ThinkPHP5')
//     {
//         return 'hello,' . $name;
//     }
}

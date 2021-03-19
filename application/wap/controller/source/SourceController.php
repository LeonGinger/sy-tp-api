<?php

namespace app\wap\controller\Source;

use app\wap\controller\Base;
use app\common\enums\ErrorCode;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;
use think\facade\Validate;
use think\facade\Config;
use think\route\Resource;

/**
 * 用户相关
 */
class SourceController extends Base
{
    private $table = 'Source';
    // 操作员扫码出入库接口
    function oped_source(){
      $userid = $this->uid;
      $code = $this->request->param('source_code');
    }
    
    

}
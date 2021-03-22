<?php

namespace app\admin\controller\region;

use app\admin\controller\BaseCheckUser;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use think\Queue;
use redis\Redis;

use app\model\Area;
use app\model\City;
use app\model\Province;

/**
 * 溯源码
 */
class RegionController extends BaseCheckUser
{
    /*省市级数据 */
    public function get_region(){
        $result = Province::with(['children'=>function($query){
            $query->with([
                'children'=>function($query){
                    $query->field('id,name,area_code,city_code,id as value,name as label');
                }
            ])->field('id,name,city_code,province_code,id as value,name as label');
        }])->field('id,name,province_code,id as value,name as label')->select();
        return ResultVo::success($result);
    }
}
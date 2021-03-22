<?php

namespace app\model;

use think\Model;

class City extends Model
{
    protected $table = 'city';
    /* 获取下面的区 */
    public function children(){
        return $this->hasMany('area','city_code','city_code');
    }
    
}

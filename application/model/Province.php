<?php

namespace app\model;

use think\Model;

class Province extends Model
{
    protected $table = 'province';
    /*获取下面的市*/
    public function children(){
        return $this->hasMany('city','province_code','province_code');
    }
    public function Area(){
        return $this->belongsToMany('area','city','area_code','area_code');
    }
}

<?php

namespace app\model;

use think\Model;

class source_log extends Model
{
    //
    public function Source(){
        return $this->hasMany('Source','source_code','source_code');
    }
}

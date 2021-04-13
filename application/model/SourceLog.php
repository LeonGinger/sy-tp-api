<?php

namespace app\model;

use think\Model;

class SourceLog extends Model
{
    //
    protected $table = 'source_log';
    public function Source(){
        return $this->hasMany('Source','source_code','source_code');
    }
}

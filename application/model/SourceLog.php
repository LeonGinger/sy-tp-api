<?php

namespace app\model;

use think\Model;

class SourceLog extends Model
{
    protected $table = 'source_log';
    public function Source(){
        return $this->hasMany('Source','source_code','source_code');
    }
    /*对应的商品*/
    public function Gmemu(){
    	return $this->hasOne('menu','id','menu_id');
    }
    /*对应的用户*/
     public function Guser(){
    	return $this->hasOne('user','id','user_id');
    }
}

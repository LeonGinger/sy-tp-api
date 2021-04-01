<?php

namespace app\model;

use think\Model;

class Menu extends Model
{
    //
    protected $table = 'menu';
    public function MenuMonitor(){
        return $this->hasMany('MenuMonitor','menu_id','id');
    }
    public function MenuCertificate(){
        return $this->hasMany('MenuCertificate','menu_id','id');
    }
}

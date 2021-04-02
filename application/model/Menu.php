<?php

namespace app\model;

use think\Model;

class Menu extends Model
{
    //
    protected $table = 'view_menu';
    public function RecommendMenu(){
    	return $this->hasOne('MenuCertificate','menu_id','id');
    }
    public function MonitorMenu(){
    	return $this->hasOne('MenuMonitor','menu_id','id');
    }
    public function CertificateMenu(){
    	return $this->hasOne('MenuCertificate','menu_id','id');
    }
    public function hasBusiness(){
    	return $this->hasOne('business','id','business_id');
    }
    /**
     * 历史图片
     * 待添加hasMany
     * code......
    */
    // 
}

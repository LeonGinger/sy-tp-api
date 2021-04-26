<?php

namespace app\model;

use think\Model;

class Menu extends Model
{
    /**
     * 仅用于查询
     */
    // protected $table = 'menu';
    protected $table = 'view_menu';
    //热卖
    public function RecommendMenu(){
    	return $this->hasOne('RecommendMenu','menu_id','id');
    }
    //商品检测报告
    public function MonitorMenu(){
    	return $this->hasOne('MenuMonitor','menu_id','id');
    }
    //商品证书
    public function CertificateMenu(){
    	return $this->hasOne('MenuCertificate','menu_id','id');
    }
    //商家信息
    public function hasBusiness(){
    	return $this->hasOne('business','id','business_id');
    }

    /**
     * 历史图片
     * 待添加hasMany
     * code......
    */
    // 
    // protected $table = 'menu';
    public function MenuMonitor(){
        return $this->hasMany('MenuMonitor','menu_id','id');
    }
    public function MenuCertificate(){
        return $this->hasMany('MenuCertificate','menu_id','id');
    }

    // 获取溯源码数量
    public function CoutSourcecode(){
        return $this->hasOne('source','menu_id','id');
    }
}

<?php

namespace app\model;

use think\Model;

class business extends Model
{
    protected $table = 'business';
    public function BusinessAppraisal(){
        return $this->hasMany('BusinessAppraisal','id','business_appraisal_id');
    }
    public function BusinessImg(){
        return $this->hasMany('BusinessImg','business_id','id');
    }
    public function BossUser(){
    	return $this->hasOne('user','business_notice','id');
    }
    
}

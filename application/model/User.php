<?php

namespace app\model;

use think\Model;

class User extends Model
{
    //
    protected $table = 'user';
    //protected $table = 'view_user';
    public function getRole(){
    	return $this->hasOne('role','id','role_id')->field('id,role_name,remark');
    }
    public function hasBusiness(){

    	return $this->hasOne('business','id','business_notice');
    }
}

//商家字段
// id
// business_name
// responsible_name
// responsible_phone
// business_address
// business_appraisal_id
// business_images
// img_info
// business_introduction
// delete_time
// create_time
// verify_if
// grant_code
// state

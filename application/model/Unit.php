<?php
namespace app\model;

use think\Model;

class Unit extends Model
{
	protected $table = 'unit';
    //商家信息
    public function hasBusiness(){
    	return $this->hasOne('business','id','business_id');
    }
}
<?php

namespace app\wap\controller\business;

use app\wap\controller\Base;
use app\common\enums\ErrorCode;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;

/**
 * 商家相关
 */
class BusinessController extends Base
{

    /**
     * 
     */
    public function Apply_add()
    {
   
        $res=  $this->WeDb->find('auth_admin','id = 1');
        var_dump($res);exit;
        $re_data = array(
            'business_name'=>$this->request->param('business_name'),
            'business_address'=>$this->request->param('business_address'),
            'responsible_name'=>$this->request->param('responsible_name'),
            'responsible_phone'=>$this->request->param('responsible_phone'),
            'user_id'=>$this->uid,
            'business_appraisal_id'=>$this->request->param('business_appraisal_id'),
            // 'business_introduction'=>$this->request->param('business_introduction'),
        );
        $res = $this->WeDb->insert('',$re_data);
        return ResultVo::success();
    }

    /**
     * 证书上传
     */
    public function Upload()
    {


    }
}
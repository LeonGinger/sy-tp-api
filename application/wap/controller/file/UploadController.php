<?php

namespace app\wap\controller\file;

use app\wap\controller\Base;
use app\common\enums\ErrorCode;
use app\common\utils\PublicFileUtils;
use app\common\vo\ResultVo;
use think\facade\Env;
use think\File;
use think\facade\Config;
use think\facade\Validate;

/**
 * 上传文件（管理文件的）
 * Class UploadFile
 * @package app\admin\controller
 */
class UploadController extends Base
{

    /**
     * 上传token
     */
    public function qiuNiuUpToken()
    {

        $res = [];
        $res["upload_url"] = PublicFileUtils::getUploadBaseUrl();
        $res["up_token"] = "xxxxxxxx";
        $res["domain"] = PublicFileUtils::getDomainBaseUrl();

        return ResultVo::success($res);
    }


    public function createFile()
    {
        /**
         * @var File $uploadFile
         */
        if (!request()->isPost()) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }

        // 上传文件
        $uploadName = request()->param('uploadName');
        $uploadName = !empty($uploadName) ? $uploadName : "file";
        $uploadFile = request()->file($uploadName);
        if (empty($uploadFile)) {
            return ResultVo::error(ErrorCode::DATA_NOT, "没有文件上传");
        }

        $exts = request()->param("exts");
        $size = request()->param("size/d");
        $filePath = request()->param("filePath");
        $config = [];
        if ($size > 0) {
            $config['size'] = $size;
        }
        if ($exts) {
            $config['ext'] = $exts;
        }
        $basepath = Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
        $filePath = (!empty($filePath) ? $filePath : "resources") . DIRECTORY_SEPARATOR;
        $filepath = $basepath . $filePath;
        $info = $uploadFile->validate($config)->move($filepath);
        if (!$info) {
            return ResultVo::error(ErrorCode::DATA_NOT, $uploadFile->getError());
        }

        $saveName = $info->getSaveName();
        $path = $filePath . $saveName;
        $path = str_replace("\\", "/", $path);

        $res = [];
        $res["key"] = $path;
        return ResultVo::success($res);
    }

    /**
     * 前端通用的上传图片接口
     */
    public function Img_Allupload(){

        $file = request()->file('imgurl');  

        if($file == null){
          return ResultVo::error(ErrorCode::UPLOAD_IS_NULL['code'], ErrorCode::UPLOAD_IS_NULL['message']);
        } 
        $info = $file->validate(['ext' => 'jpg,jpeg,png'])
          ->move(Config::get('upload_resources'));
          // var_dump($file);
          // exit;
        $source = Config::get('upload_resources').$info->getSaveName();
        // $url = Config::get('domain_http').''.$info->getSaveName();
        $url  = str_replace(Config::get('upload_resources'), Config::get('domain_http') . 'uploads/resources/', $source);
        /**
         * 大小压缩
         * code....  
         **/
        /*返回头像地址 */
        $re_data = array(
          'link' => $url,
          'dir' => $source,
        );
        return ResultVo::success($re_data);
    
    
    }

}
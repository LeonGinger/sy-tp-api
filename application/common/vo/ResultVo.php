<?php

namespace app\common\vo;

class ResultVo
{

    /**
     * 错误码
     * @var
     */
    public $code;

    /**
     * 错误信息
     * @var
     */
    public $message;

    /**
     * data
     * @var
     */
    public $data;

    private function __construct($code, $message, $data)
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * 请求成功的方法
     * @param $data
     * @return \think\response\Json
     */
    public static function success($data = null)
    {
        if (empty($data)) {
            $data = new \stdClass();
        }
        $instance = new self(200, "success", $data);
        return json($instance);
    }

    /**
     * 请求错误
     * @param $code
     * @param null $message
     * @return \think\response\Json
     */
    public static function error($code, $message = null,$data =[])
    {
   
        if (is_array($code)) {
            $message = isset($code['message']) && $message == null ? $code['message'] : $message;
            $code = isset($code['code']) ? $code['code'] : null;
        }
        if (empty($data)) {
            $data = new \stdClass();
        }

        $instance = new self($code, $message,$data);

        return json($instance);
    }
    /**
     * 请求错误
     * @param $code
     * @param null $message
     * @param data $data
     * @return \think\response\Json
     */
    public static function json($code,$message=null,$data){
        if (is_array($code)) {
            $message = isset($code['message']) && $message == null ? $code['message'] : $message;
            $code = isset($code['code']) ? $code['code'] : null;
        }
        if (empty($data)) {
            $data = new \stdClass();
        }

        $instance = new self($code, $message,$data);

        return json($instance);

    }

}
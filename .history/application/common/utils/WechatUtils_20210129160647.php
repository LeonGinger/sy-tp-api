<?php
namespace app\common\utils;
/**
 * 微信工具类
 */
class WechatUtils{

    private static $h5config = [
        'app_id' => 'wxed93066ca98ccfb9',
        'secret' => '667f1a4cf236867c8ad3a36fb4ab8938',
        'token' => '0b60f61f01ba6cb292b8ce3b5e822fbc',
        // 测试号
        // 'app_id' => 'wxb16eae149faa2114',
        // 'secret' => 'bbc143ffd6aea297ae826a588960dd00',

        'key'    => '87e345eecc2fb425c3f39f1b9218a709',   
        // API 密钥 A82DC5BD1F3359081049C568D8502BC5
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',
        'mch_id'     => '1330381101',
        'mch_key'    => '87e345eecc2fb425c3f39f1b9218a709',
        'log' => [
            'level' => 'debug',
            'permission' => 0777,
            'file' => RUNTIME_PATH. 'wechat/wxpay.log',
        ],

    ];

    private static $Econfig = [];

}
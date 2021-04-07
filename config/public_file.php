<?php

// +----------------------------------------------------------------------
// | 公共文件的路径配置
// +----------------------------------------------------------------------

return [
    // 七牛云的配置
    "qiniu" => [
        "accessKey" => "",
        "secretKey" => "",
    ],
    // 一些空间的配置
    "bucket" => [
        'upload_url'   => 'http://sy.zsicp.com/web/file/uploadfile',
        'domain'   => 'http://sy.zsicp.com/uploads',
    ],
];
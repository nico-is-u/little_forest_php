<?php
/**
 * 腾讯云COS配置文件
 */
return [
    // 地域，如：ap-guangzhou, ap-shanghai, ap-beijing
    'region' => env('COS_REGION', 'ap-guangzhou'),

    // 协议，http 或 https
    'schema' => env('COS_SCHEMA', 'https'),

    // 访问凭证
    'credentials' => [
        'secretId' => env('COS_SECRET_ID', ''),
        'secretKey' => env('COS_SECRET_KEY', ''),
    ],

    // 存储桶名称
    'bucket' => env('COS_BUCKET', ''),

    // COS域名（可选，用于配置自定义域名）
    'domain' => env('COS_DOMAIN', ''),

    // 默认上传路径前缀
    'upload_path' => env('COS_UPLOAD_PATH', 'uploads'),

    // 文件访问权限：private（私有）, public-read（公共读）
    'acl' => env('COS_ACL', 'private'),

    // 临时链接访问时间
    'expire' => 600,
];

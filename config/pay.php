<?php

return [
    'alipay' => [
        'app_id'         => '2016092100561236',
        'ali_public_key' => env('ALIPAY_PUB'),
        'private_key'    => env('ALIPAY_SEC'),
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];
<?php

return [
    'vnpay' => [
        'tmn_code' => env('VNPAY_TMN_CODE'),
        'hash_secret' => env('VNPAY_HASH_SECRET'),
        'secret_key' => env('VNPAY_SECRET_KEY'), // Hỗ trợ cả secret_key và hash_secret
        'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('VNPAY_RETURN_URL'),
        'version' => '2.1.0',
        'command' => 'pay',
        'order_type' => 'other',
        'locale' => 'vn',
        'currency' => 'VND',
    ],

    'provies' => [
        'api_url' => env('PROVIES_API_URL', 'https://provinces.open-api.vn/api'),
        'timeout' => env('PROVIES_TIMEOUT', 10),
        'cache_ttl' => env('PROVIES_CACHE_TTL', 1440), // 24 hours in minutes
    ],
];


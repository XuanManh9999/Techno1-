<?php

return [
    'vnpay' => [
        'tmn_code' => env('VNPAY_TMN_CODE'),
        'hash_secret' => env('VNPAY_HASH_SECRET'),
        'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('VNPAY_RETURN_URL'),
    ],

    'provies' => [
        'api_url' => env('PROVIES_API_URL', 'https://provinces.open-api.vn/api'),
        'timeout' => env('PROVIES_TIMEOUT', 10),
        'cache_ttl' => env('PROVIES_CACHE_TTL', 1440), // 24 hours in minutes
    ],
];


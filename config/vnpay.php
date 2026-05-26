<?php

return [
    'tmn_code'    => env('VNPAY_TMN_CODE', 'I35D0YMB'),
    'hash_secret' => env('VNPAY_HASH_SECRET', 'L1MRFUT4YOK88PEWRVZX04KJ8BYEO9UE'),
    'url'         => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'return_url'  => env('VNPAY_RETURN_URL'),
    'ipn_url'     => env('VNPAY_IPN_URL'),
    'locale'      => env('VNPAY_LOCALE', 'vn'),
];

<?php

return [
    'tmn_code' => env('VNPAY_TMN_CODE', 'GEBGNQZC'),
    'hash_secret' => env('VNPAY_HASH_SECRET', '391WOHKIIMQZIH348STZWJTF1I9LO974'),
    'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'return_url' => env('VNPAY_RETURN_URL'),
    'locale' => env('VNPAY_LOCALE', 'vn'),
];

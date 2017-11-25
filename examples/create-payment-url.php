<?php

require "vendor/autoload.php";
use SenangPay\SenangPay;

$merchantId = '848144282051638';
$secretKey = '491-308';

$senangPay = new SenangPay($merchantId, $secretKey);
$paymentUrl = $senangPay->createPayment(
    'Shopping_cart_id_30',
    24.50,
    56,
    [
        'name' => 'Testing User',
        'email' => 'email@testing.com',
        'phone' => '011223344'
    ]
);
echo $paymentUrl;

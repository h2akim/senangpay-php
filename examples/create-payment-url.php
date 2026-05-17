<?php

require "vendor/autoload.php";

use SenangPay\Client;
use SenangPay\Config;

$config = Config::make('848144282051638', '491-308');
$openApi = Client::makeOpenApi($config);

$paymentUrl = $openApi->payment()->createUrl(
    'Shopping_cart_id_30',
    '24.50',
    '56',
    [
        'name' => 'Testing User',
        'email' => 'email@testing.com',
        'phone' => '011223344',
    ]
);

echo $paymentUrl . PHP_EOL;

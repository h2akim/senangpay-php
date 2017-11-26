## Third-party senangPay PHP Library

#### Installation
```bash
composer require h2akim/senangpay-php:dev-master
```


#### Usage

```bash
<?php

use SenangPay\SenangPay;

$merchantId = 'xxx';
$secretKey = 'xxx';

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

```

#### Note

:bangbang: This library is not tested as author does not have senangPay account. :bangbang:

If you find any bugs or mistake, please add an issue or send pull request

##### License
MIT
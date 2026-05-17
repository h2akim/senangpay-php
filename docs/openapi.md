## OpenApi

Create payment URLs and verify callback hashes.

### Create payment URL

```
$url = $openApi->payment()->createUrl(
    detail: 'Order #1234',
    amount: '24.50',
    orderId: '56',
    options: [
        'name' => 'Testing User',
        'email' => 'email@testing.com',
        'phone' => '011223344',
    ],
);

echo $url; // https://app.senangpay.my/payment/...
```

The hash is computed over `$secretKey . $detail . $amount . $orderId` using configured `hashType` (md5 or sha256).

### Verify callback

```
$data = $_POST; // or request()->all() in Laravel
$verified = $openApi->payment()->verifyCallback($data);

if ($verified) {
    // $verified contains ['status_id', 'order_id', 'transaction_id', 'msg', 'hash']
    // 'hash' is removed from the returned array after verification
}
```

Returns the input array minus `hash` on success, empty array on failure.

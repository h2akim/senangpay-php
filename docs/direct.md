## Direct

Create direct client sessions and retrieve FPX bank list.

### Create payment session

```
$response = $direct->payment()->createSession([
    'customer_name' => 'Testing User',
    'customer_email' => 'email@testing.com',
    'customer_phone' => '011223344',
    'order_id' => '56',
    'amount' => '24.50',
    'detail' => 'Order #1234',
    'payment_method' => 'fpx',
]);

$response->toArray();
// ['status' => 'success', 'url' => 'https://...', ...]
```

Hash computed over `$secretKey . customer_name . customer_email . customer_phone . order_id . amount . detail . payment_method`.

### List FPX banks

```
$response = $direct->fpxBank()->list();

$response->toArray();
// ['status' => 'success', 'bank_list' => [...]]
```

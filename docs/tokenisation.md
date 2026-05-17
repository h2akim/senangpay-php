## Tokenisation

Pay with saved tokens, update token status, and validate tokens.

### Pay with token

```
$response = $tokenisation->token()->pay([
    'name' => 'Testing User',
    'email' => 'email@testing.com',
    'phone' => '011223344',
    'detail' => 'Order #1234',
    'order_id' => '56',
    'amount' => '24.50',
]);

$response->toArray();
// ['status' => 'success', ...]
```

Hash computed over `$merchantId . name . email . phone . detail . order_id . amount`.

### Update token status

```
$response = $tokenisation->token()->updateStatus('TOKEN_VALUE');

$response->toArray();
// ['status' => 'success', ...]
```

### Validate token

```
$response = $tokenisation->token()->validate('TOKEN_VALUE');

$response->toArray();
// ['status' => 'valid', ...]
```

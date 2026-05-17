## Recurring

Create recurring billing products.

### Create product

```
$response = $recurring->product()->create([
    'name' => 'Monthly Subscription',
    'price' => '49.90',
    'code' => 'SUB-MONTHLY-001',
]);

$response->toArray();
// ['status' => 'success', ...]
```

Hash computed over `$secretKey . name . price . code`.

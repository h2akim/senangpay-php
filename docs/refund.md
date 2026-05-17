## Refund

Issue refunds against transactions. Uses bearer token authentication (fetches token automatically).

### Create a refund

```
$response = $refund->refund()->create(
    transactionReference: 'T12345678',
    refundAmount: '10.00',
);

$response->toArray();
// ['status' => 'success', ...]
```

The client automatically fetches an API token via `merchant/get_token` before sending the refund request.

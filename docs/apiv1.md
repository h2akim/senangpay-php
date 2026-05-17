## ApiV1

Query order payment status, transaction status, and transaction lists.

### Payment status by order ID

```
$response = $apiV1->order()->paymentStatus('56');

$response->toArray();
// ['status' => '1', 'order_id' => '56', ...]
```

### Single transaction status

```
$response = $apiV1->transaction()->status('T12345678');

$response->toArray();
// ['status' => '1', 'transaction_id' => 'T12345678', ...]
```

### Transaction list (by date range)

```
$response = $apiV1->transactions()->list(
    timestampStart: 1700000000,
    timestampEnd: 1700086400,
);

$response->toArray();
// ['status' => '1', 'transaction_list' => [...], ...]
```

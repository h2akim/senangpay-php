## Payout

Submit merchant payouts. Uses Basic Auth + SHA256 signature.

### Submit payout

```
$response = $payout->payout()->submit(
    data: [
        'recipient' => [
            'name' => 'Recipient Name',
            'email' => 'recipient@example.com',
            'bank_code' => 'MBBEMYKL',
            'account_number' => '1234567890',
        ],
        'amount' => '100.00',
    ],
    callbackUrl: 'https://example.com/callback',
    orderId: 'PO-001',
);

$response->toArray();
// ['status' => 'success', ...]
```

The signature is a `Checksum::create()` over the request body using SHA256.

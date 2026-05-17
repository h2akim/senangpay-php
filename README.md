# PHP framework agnostic to work with senangPay API

## Installation

```bash
composer require php-http/guzzle7-adapter h2akim/senangpay-php
```

#### HTTP Adapter

Refer [PHP-HTTP Clients & Adapters](http://docs.php-http.org/en/latest/clients.html) for other supported clients and adapters.

## Get Started

### Creating client

```php
use SenangPay\Client;
use SenangPay\Config;
use Laravie\Codex\Discovery;

$http = Discovery::client();

// OpenApi — payment URL creation + callback verification
$openApi = Client::makeOpenApi($http, new Config(
    merchantId: 'your_merchant_id',
    secretKey: 'your_secret_key',
));

// ApiV1 — query order/transaction status
$apiV1 = Client::makeApiV1($http, new Config(
    merchantId: 'your_merchant_id',
    secretKey: 'your_secret_key',
));

// Direct — client session + FPX bank list (Basic Auth)
$direct = Client::makeDirect($http, new Config(
    merchantId: 'your_merchant_id',
    secretKey: 'your_secret_key',
));

// Refund — get token → create refund (Bearer token)
$refund = Client::makeRefund($http, new Config(
    merchantId: 'your_merchant_id',
    secretKey: 'your_secret_key',
));

// Payout — submit payout (Basic Auth + SHA256)
$payout = Client::makePayout($http, new Config(
    merchantId: 'your_merchant_id',
    secretKey: 'your_secret_key',
));

// Tokenisation — token pay/updateStatus/validate
$tokenisation = Client::makeTokenisation($http, new Config(
    merchantId: 'your_merchant_id',
    secretKey: 'your_secret_key',
));

// Recurring — create recurring product (Basic Auth)
$recurring = Client::makeRecurring($http, new Config(
    merchantId: 'your_merchant_id',
    secretKey: 'your_secret_key',
));
```

### Config

```php
use SenangPay\Config;

// Default (production, hash md5)
$config = new Config(
    merchantId: 'xxx',
    secretKey: 'xxx',
);

// Sandbox + SHA256
$config = new Config(
    merchantId: 'xxx',
    secretKey: 'xxx',
    hashType: 'sha256',
    sandbox: true,
);

// Custom version
$config = new Config(
    merchantId: 'xxx',
    secretKey: 'xxx',
    version: 'v1',
);

// Custom base URI (overrides all defaults)
$config = new Config(
    merchantId: 'xxx',
    secretKey: 'xxx',
    baseUri: 'https://custom.example.com',
);
```

### Use Sandbox

Set `sandbox: true` in Config to use sandbox endpoints. Domains with sandbox support:

| Domain | Sandbox URL |
|--------|------------|
| OpenApi | `https://sandbox.senangpay.my` |
| ApiV1 | `https://sandbox.senangpay.my/apiv1` |
| Direct | `https://api.sandbox.senangpay.my` |
| Refund | `https://api.sandbox.senangpay.my` |
| Payout | `https://api.sandbox.senangpay.my` |
| Recurring | `https://api.sandbox.senangpay.my/recurring` |

Tokenisation does not support sandbox.

## Domains

| Domain | Description | Resource |
|--------|-------------|----------|
| [OpenApi](docs/openapi.md) | Payment URL creation & callback verification | `$openApi->payment()` |
| [ApiV1](docs/apiv1.md) | Order/transaction status queries | `$apiV1->order()`, `->transaction()`, `->transactions()` |
| [Direct](docs/direct.md) | Client session & FPX bank list | `$direct->payment()`, `->fpxBank()` |
| [Refund](docs/refund.md) | Token-based refunds | `$refund->refund()` |
| [Payout](docs/payout.md) | Merchant payout submissions | `$payout->payout()` |
| [Tokenisation](docs/tokenisation.md) | Token pay, status update, validation | `$tokenisation->token()` |
| [Recurring](docs/recurring.md) | Recurring product creation | `$recurring->product()` |

## License

MIT

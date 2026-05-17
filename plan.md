# senangpay-php SDK Upgrade Plan

## Current State

```
senangpay-php/
├── composer.json              # PSR-4: SenangPay\ -> src/
├── src/
│   └── SenangPay.php          # Single 123-line class (monolithic)
├── examples/
│   └── create-payment-url.php # One example
└── README.md
```

Covers: only Open API payment URL creation + callback verification.

## Target: chip-php-sdk Architecture

```
chip-php-sdk/
├── composer.json              # PSR-4: Chip\ -> src/
├── phpunit.xml
├── src/
│   ├── Client.php             # Static factory (makeCollect, makeSend)
│   ├── Config.php             # Immutable config DTO
│   ├── Collect.php            # Collect domain client (extends Codex\Client)
│   ├── Send.php               # Send domain client (extends Codex\Client)
│   ├── Exceptions/            # Custom exceptions
│   └── Services/
│       ├── Common/
│       │   ├── BaseRequest.php
│       │   └── BaseResponse.php
│       ├── Collect/
│       │   ├── Request.php    # Bearer auth + version prefix
│       │   ├── Response.php
│       │   ├── Sanitizer.php
│       │   ├── Contracts/     # Interfaces per resource
│       │   ├── Models/        # DTOs
│       │   └── One/           # v1 implementations
│       └── Send/
│           ├── Request.php    # HMAC checksum auth
│           ├── Response.php
│           ├── Sanitizer.php
│           ├── Checksum.php
│           ├── Contracts/
│           └── One/
├── tests/
│   ├── CollectClientTest.php
│   ├── SendClientTest.php
│   └── Support/
│       └── FakeHttpMethodsClient.php
└── docs/
```

## Proposed senangpay-php Architecture

```
senangpay-php/
├── composer.json              # PSR-4: SenangPay\ -> src/
├── phpunit.xml
├── src/
│   ├── Client.php             # Static factory: makeOpenApi(), makeApiV1(), makeDirect()...
│   ├── Config.php             # Immutable config (merchantId, secretKey, hashType, sandbox)
│   ├── OpenApi.php            # Domain client (extends Laravie\Codex\Client)
│   ├── ApiV1.php              # Domain client (extends Laravie\Codex\Client)
│   ├── Direct.php             # Domain client (extends Laravie\Codex\Client)
│   ├── Refund.php             # Domain client (extends Laravie\Codex\Client)
│   ├── Payout.php             # Domain client (extends Laravie\Codex\Client)
│   ├── Tokenisation.php       # Domain client (extends Laravie\Codex\Client)
│   ├── Recurring.php          # Domain client (extends Laravie\Codex\Client)
│   ├── Exceptions/
│   │   └── InvalidArgumentException.php
│   └── Services/
│       ├── Common/
│       │   ├── BaseRequest.php    # abstract extends Laravie\Codex\Request
│       │   ├── BaseResponse.php   # extends Laravie\Codex\Response
│       │   └── Hasher.php         # MD5 / HMAC-SHA256 static helpers
│       ├── OpenApi/
│       │   ├── Request.php        # extends Common\BaseRequest, adds hash param auth
│       │   ├── Response.php       # extends Common\BaseResponse
│       │   ├── Sanitizer.php      # extends Laravie\Codex\Filter\Sanitizer
│       │   ├── Contracts/
│       │   │   └── Payment.php    # createUrl(), verifyCallback()
│       │   └── V1/                # version namespace ("V1" from supportedVersions)
│       │       └── Payment.php    # implements Contracts\Payment
│       ├── ApiV1/
│       │   ├── Request.php
│       │   ├── Response.php
│       │   ├── Sanitizer.php
│       │   ├── Contracts/
│       │   │   ├── Order.php          # paymentStatus()
│       │   │   ├── Transaction.php    # status()
│       │   │   └── Transactions.php   # list()
│       │   └── V1/
│       │       ├── Order.php
│       │       ├── Transaction.php
│       │       └── Transactions.php
│       ├── Direct/
│       │   ├── Request.php        # extends BaseRequest, adds Basic Auth
│       │   ├── Response.php
│       │   ├── Sanitizer.php
│       │   ├── Contracts/
│       │   │   ├── Payment.php    # createSession()
│       │   │   └── FpxBank.php    # list()
│       │   └── V1/
│       │       ├── Payment.php
│       │       └── FpxBank.php
│       ├── Refund/
│       │   ├── Request.php        # extends BaseRequest, adds Bearer token auth
│       │   ├── Response.php
│       │   ├── Sanitizer.php
│       │   ├── Contracts/
│       │   │   └── Refund.php     # create() — token fetch internal to Request
│       │   └── V1/
│       │       └── Refund.php
│       ├── Payout/
│       │   ├── Request.php        # extends BaseRequest, adds SHA256 hash + Basic Auth
│       │   ├── Response.php
│       │   ├── Sanitizer.php
│       │   ├── Checksum.php       # payout hash generator
│       │   ├── Contracts/
│       │   │   └── Payout.php     # send()
│       │   └── V1/
│       │       └── Payout.php
│       ├── Tokenisation/
│       │   ├── Request.php
│       │   ├── Response.php
│       │   ├── Sanitizer.php
│       │   ├── Contracts/
│       │   │   └── Token.php      # pay(), updateStatus(), validate()
│       │   └── V1/
│       │       └── Token.php
│       └── Recurring/
│           ├── Request.php
│           ├── Response.php
│           ├── Sanitizer.php
│           ├── Contracts/
│           │   └── Product.php    # create()
│           └── V1/
│               └── Product.php
├── tests/
│   ├── OpenApiTest.php
│   ├── ApiV1Test.php
│   ├── DirectTest.php
│   ├── RefundTest.php
│   ├── PayoutTest.php
│   ├── TokenisationTest.php
│   ├── RecurringTest.php
│   └── Support/
│       └── FakeHttpMethodsClient.php
├── examples/
│   ├── create-payment-url.php
│   ├── direct-v3-client-session.php
│   ├── refund.php
│   └── ...
└── docs/
    ├── open-api.md
    ├── direct-api.md
    └── ...
```

## Naming Convention

**Rule**: Proxy methods + contracts = **resource nouns**. Implementation methods = **verbs**.

```
Client::resource()->action()
       ↑ noun     ↑ verb
```

| ❌ Old (confusing) | ✅ New (resource noun) | Reason |
|---|---|---|
| `orderStatus()` → `OrderStatus` | `order()` → `Order` | "OrderStatus" is an action, not a resource |
| `transactionStatus()` → `TransactionStatus` | `transaction()` → `Transaction` | Same — querying is an action on the resource |
| `transactionList()` → `TransactionList` | `transactions()` → `Transactions` | List is an action; `Transactions` is the collection resource |
| `clientSession()` → `ClientSession` | `payment()` → `Payment` | Creates a payment session; `Payment` is the domain resource |
| `fpxBankList()` → `FpxBankList` | `fpxBank()` → `FpxBank` | Bank list is `fpxBank()->list()`, contract is the noun |
| `authentication()` → `Authentication` | *(internal — not exposed)* | Token fetch is internal to Request auth, not a user-facing service |
| `refundRequest()` → `RefundRequest` | `refund()` → `Refund` | "Request" is redundant; a Refund is the resource |
| `payoutInstruction()` → `PayoutInstruction` | `payout()` → `Payout` | "Instruction" is noise |
| `payToken()` → `PayToken` | `token()` → `Token` | Token is the resource; pay/update/validate are actions on it |
| `updateToken()` → `UpdateToken` | *(via `token()`)* | Merged into `Token` contract with multiple methods |
| `validateToken()` → `ValidateToken` | *(via `token()`)* | Merged into `Token` contract |
| `product()` → `ProductRecurring` | `product()` → `Product` | Recurring product is a `Product`; "Recurring" is noise in the name |

## API Surface Map

| senangPay Feature | SDK Domain Client | Endpoint(s) | Auth |
|---|---|---|---|
| **Open API** (Payment URL + Callback) | `SenangPay\OpenApi` | GET/POST `app.senangpay.my/payment/{id}` | Hash param |
| **Query Order Status** | `SenangPay\ApiV1` | GET `app.senangpay.my/apiv1/query_order_status` | Hash param |
| **Query Transaction Status** | `SenangPay\ApiV1` | GET `app.senangpay.my/apiv1/query_transaction_status` | Hash param |
| **Get Transaction List** | `SenangPay\ApiV1` | GET `app.senangpay.my/apiv1/get_transaction_list` | Hash param |
| **Direct API v3** (Client Session) | `SenangPay\Direct` | POST `api.senangpay.my/payment/v3` | Basic Auth (merchant_id) |
| **FPX Bank List** | `SenangPay\Direct` | GET `api.senangpay.my/fpx_bank_list` | Basic Auth (merchant_id) |
| **Refund** (create) | `SenangPay\Refund` | GET `/merchant/get_token`, POST `/merchant_service/create_refund_request` | Bearer token |
| **Payout** | `SenangPay\Payout` | POST `api.senangpay.my/payout_api` | Basic Auth + SHA256 hash |
| **Tokenisation** (pay/update/validate) | `SenangPay\Tokenisation` | POST `app.senangpay.my/apiv1/pay_cc` etc. | Basic Auth |
| **Recurring** (create product) | `SenangPay\Recurring` | POST `api.senangpay.my/recurring/product/create` | Basic Auth |

## Key Design Decisions

### 1. Use Laravie\Codex + Laravie\Codex-Filter (same as chip-php-sdk)
Mirror the chip SDK pattern exactly:
- Domain clients (`OpenApi`, `ApiV1`, etc.) extend `Laravie\Codex\Client`
- Versioned implementations in `Services/{Domain}/V1/` extend `Services/{Domain}/Request`
- `Services/{Domain}/Request` extends `Services/Common/BaseRequest` which extends `Laravie\Codex\Request`
- `Services/{Domain}/Sanitizer` extends `Laravie\Codex\Filter\Sanitizer`
- Contracts (interfaces) extend `Laravie\Codex\Contracts\Request`
- `uses()` auto-resolves version namespace via `supportedVersions` + `getResourceNamespace()`

Use the same forked repos from `git@github.com:h2akim/codex.git` etc.

### 2. Common/BaseRequest and Common/BaseResponse
- `BaseRequest` extends `Laravie\Codex\Request`, provides `Hasher` integration
- `BaseResponse` extends `Laravie\Codex\Response`, provides `mapTo()` / `mapToList()` via `json-mapper/json-mapper`

### 3. Domain clients map to senangPay API groups
senangPay has 7 API groups with different auth mechanisms. Each gets its own domain client:

| Client | Auth | Endpoints |
|---|---|---|
| `OpenApi` | Hash param in URL | Payment URL creation, callback verification |
| `ApiV1` | Hash param in URL | query_order_status, query_transaction_status, get_transaction_list |
| `Direct` | Basic Auth (merchant_id) | Payment v3 client session, FPX bank list |
| `Refund` | Bearer token | create_refund_request (token internal) |
| `Payout` | Basic Auth + SHA256 hash | payout_api |
| `Tokenisation` | Basic Auth (merchant_id) | pay_cc, update_token_status, validate_token |
| `Recurring` | Basic Auth (merchant_id) | recurring/product/create |

### 4. Auth patterns implemented in domain Request classes
- **Hash param**: `OpenApi\Request` / `ApiV1\Request` — appends `hash` to query/body via Hasher
- **Basic Auth**: `Direct\Request`, `Tokenisation\Request`, `Recurring\Request` — sets `Authorization: Basic base64(merchant_id:)`
- **Bearer token**: `Refund\Request` — sets `Authorization: Bearer {token}` obtained from `Authentication` service
- **Custom hash**: `Payout\Request` — generates SHA256 hash of `{secret_key}{merchant_id}{timestamp}`

### 5. HashType config
`Config::$hashType`: `'md5'` | `'sha256'` (default). Controls which algorithm `Hasher` uses.

### 6. Backward compatibility
Keep `SenangPay\SenangPay` class but deprecate. New usage: `Client::makeOpenApi()`, etc.

### 7. Sandbox support
`Config::$sandbox` switches domain endpoints:
- `sandbox.senangpay.my` ↔ `app.senangpay.my`
- `api.sandbox.senangpay.my` ↔ `api.senangpay.my`

### 8. Dependencies
```
require:
  php: ^8.2
  laravie/codex: ^6.2          # (forked from h2akim/codex)
  laravie/codex-filter: ^2.1   # (forked from h2akim/codex-filter)
  symfony/http-client: ^7.2
  json-mapper/json-mapper: ^2.22 (for response mapping)

require-dev:
  phpunit/phpunit: ^10.5
  php-http/guzzle7-adapter: ^1.1

repositories (all forked to h2akim/*):
  laravie/codex       -> git@github.com:h2akim/codex.git
  laravie/codex-filter -> git@github.com:h2akim/codex-filter.git
  laravie/codex-security -> git@github.com:h2akim/codex-security.git
  laravie/codex-common -> git@github.com:h2akim/codex-common.git
```

## Implementation Order

| Step | Scope | Effort |
|---|---|---|
| 1 | `composer.json`, `phpunit.xml`, `Config.php`, `Exceptions/`, base classes | Scaffolding |
| 2 | `Hasher.php`, `Services/Common/` | Foundation |
| 3 | `Client.php` + `OpenApi` domain (payment URL + callback) | Core (replace existing) |
| 4 | `ApiV1` domain (query order/transaction/list) | Medium |
| 5 | `Direct` domain (client session + fpx bank list) | Medium |
| 6 | `Refund` domain (get token + create refund) | Medium |
| 7 | `Payout` domain | Low (enterprise only) |
| 8 | `Tokenisation` domain | Low (enterprise only) |
| 9 | `Recurring` domain | Low (enterprise only) |
| 10 | Tests + FakeHttpClient | Ongoing per domain |
| 11 | Examples + docs | Polish |

### Step 1 detail: Scaffolding

`composer.json`
```json
{
  "name": "h2akim/senangpay-php",
  "description": "SenangPay Payment Gateway PHP Library (unofficial)",
  "type": "library",
  "license": "MIT",
  "authors": [...],
  "autoload": {
    "psr-4": { "SenangPay\\": "src/" }
  },
  "autoload-dev": {
    "psr-4": { "SenangPay\\Tests\\": "tests/" }
  },
  "require": {
    "php": "^8.2",
    "laravie/codex": "^6.2",
    "laravie/codex-filter": "^2.1",
    "json-mapper/json-mapper": "^2.22",
    "symfony/http-client": "^7.2"
  },
  "require-dev": {
    "phpunit/phpunit": "^10.5",
    "php-http/guzzle7-adapter": "^1.1"
  },
  "repositories": {
    "laravie/codex": {
      "type": "vcs",
      "url": "git@github.com:h2akim/codex.git"
    },
    "laravie/codex-filter": {
      "type": "vcs",
      "url": "git@github.com:h2akim/codex-filter.git"
    },
    "laravie/codex-security": {
      "type": "vcs",
      "url": "git@github.com:h2akim/codex-security.git"
    },
    "laravie/codex-common": {
      "type": "vcs",
      "url": "git@github.com:h2akim/codex-common.git"
    }
  },
  "config": {
    "allow-plugins": {
      "php-http/discovery": true
    }
  }
}

`Config.php` — immutable DTO: merchantId, secretKey, hashType, sandbox, baseUri. With `with*()` methods.

`Services/Common/BaseRequest.php` — extends `Laravie\Codex\Request`, integrates Hasher.

`Services/Common/BaseResponse.php` — extends `Laravie\Codex\Response`, provides `mapTo()` / `mapToList()`.

`Services/Common/Hasher.php` — static helpers: `md5(string)`, `hmacSha256(string, key)`.

## Class Relationships (ASCII)

```
Client (static factory)
├── makeOpenApi(Config, ?HttpClient)  → OpenApi  (extends Laravie\Codex\Client)
├── makeApiV1(Config, ?HttpClient)    → ApiV1    (extends Laravie\Codex\Client)
├── makeDirect(Config, ?HttpClient)   → Direct   (extends Laravie\Codex\Client)
├── makeRefund(Config, ?HttpClient)   → Refund   (extends Laravie\Codex\Client)
├── makePayout(Config, ?HttpClient)   → Payout   (extends Laravie\Codex\Client)
├── makeTokenisation(Config, ?HttpClient) → Tokenisation (extends Laravie\Codex\Client)
└── makeRecurring(Config, ?HttpClient)→ Recurring(extends Laravie\Codex\Client)

Each domain client (e.g. OpenApi):
  - Extends Laravie\Codex\Client
  - Has $supportedVersions = ['v1' => 'V1']
  - Declares proxy methods calling $this->uses('ServiceName', $version)
    e.g.: payment(?string $version = null): Contracts\Payment
  - getResourceNamespace(): returns 'SenangPay\Services\OpenApi'
  - Configures apiEndpoint, defaultVersion

Laravie\Codex\Client::uses('Payment', 'v1') resolves to:
  {getResourceNamespace()}\{versionMap['v1']}\{resource}
  = SenangPay\Services\OpenApi\V1\Payment

Implementation hierarchy:
  Laravie\Codex\Request
    └── SenangPay\Services\Common\BaseRequest
          └── SenangPay\Services\OpenApi\Request  (adds hash auth)
                └── SenavV1\Payment  (implements Contracts\Payment, adds sendJson/sendGet calls)

  Laravie\Codex\Response
    └── SenangPay\Services\Common\BaseResponse
          └── SenangPay\Services\OpenApi\Response

  Laravie\Codex\Filter\Sanitizer
    └── SenangPay\Services\OpenApi\Sanitizer

Contract interface (extends Laravie\Codex\Contracts\Request):
  SenangPay\Services\OpenApi\Contracts\Payment

Domain client proxy methods:
  OpenApi::payment() → uses('Payment', $version) → V1\Payment implements Contracts\Payment
    ├── createUrl(detail, amount, orderId, options): string
    └── verifyCallback(data): array|false

  ApiV1::order() → uses('Order', $version) → V1\Order implements Contracts\Order
    └── paymentStatus(orderId): Response

  ApiV1::transaction() → uses('Transaction', $version) → V1\Transaction implements Contracts\Transaction
    └── status(transactionReference): Response

  ApiV1::transactions() → uses('Transactions', $version) → V1\Transactions implements Contracts\Transactions
    └── list(timestampStart, timestampEnd): Response

  Direct::payment() → uses('Payment', $version) → V1\Payment implements Contracts\Payment
    └── createSession(params): Response

  Direct::fpxBank() → uses('FpxBank', $version) → V1\FpxBank implements Contracts\FpxBank
    └── list(): Response

  Refund::refund() → uses('Refund', $version) → V1\Refund implements Contracts\Refund
    ├── create(transactionReference, refundAmount): Response
    └── (token fetch handled internally by Request auth)

  Payout::payout() → uses('Payout', $version) → V1\Payout implements Contracts\Payout
    └── send(data, callbackUrl?, orderId?): Response

  Tokenisation::token() → uses('Token', $version) → V1\Token implements Contracts\Token
    ├── pay(params): Response
    ├── updateStatus(token): Response
    └── validate(token): Response

  Recurring::product() → uses('Product', $version) → V1\Product implements Contracts\Product
    └── create(params): Response
```

## Files to Create (Step 1 - Minimum Viable: OpenApi domain)

```
src/Client.php
src/Config.php
src/Exceptions/InvalidArgumentException.php
src/Services/Common/BaseRequest.php
src/Services/Common/BaseResponse.php
src/Services/Common/Hasher.php
src/Services/OpenApi/Request.php
src/Services/OpenApi/Response.php
src/Services/OpenApi/Sanitizer.php
src/Services/OpenApi/Contracts/Payment.php
src/Services/OpenApi/V1/Payment.php
src/OpenApi.php                              # domain client extending Codex\Client
tests/Support/FakeHttpMethodsClient.php
tests/OpenApiTest.php
phpunit.xml
composer.json (update)
examples/create-payment-url.php (update)
```

Then repeat this pattern for remaining domains: ApiV1, Direct, Refund, Payout, Tokenisation, Recurring.

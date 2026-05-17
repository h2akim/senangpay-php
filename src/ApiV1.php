<?php

declare(strict_types=1);

namespace SenangPay;

class ApiV1 extends BaseClient
{
    protected $apiEndpoint = 'https://app.senangpay.my/apiv1';

    protected $sandboxEndpoint = 'https://sandbox.senangpay.my/apiv1';

    public function order(?string $version = null): Services\ApiV1\Contracts\Order
    {
        return $this->uses('Order', $version);
    }

    public function transaction(?string $version = null): Services\ApiV1\Contracts\Transaction
    {
        return $this->uses('Transaction', $version);
    }

    public function transactions(?string $version = null): Services\ApiV1\Contracts\Transactions
    {
        return $this->uses('Transactions', $version);
    }

    protected function getResourceNamespace(): string
    {
        return sprintf('%s\%s', __NAMESPACE__, 'Services\\ApiV1');
    }
}

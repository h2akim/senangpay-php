<?php

declare(strict_types=1);

namespace SenangPay;

class OpenApi extends BaseClient
{
    protected $apiEndpoint = 'https://app.senangpay.my';

    protected $sandboxEndpoint = 'https://sandbox.senangpay.my';

    public function payment(?string $version = null): Services\OpenApi\Contracts\Payment
    {
        return $this->uses('Payment', $version);
    }

    protected function getResourceNamespace(): string
    {
        return sprintf('%s\%s', __NAMESPACE__, 'Services\\OpenApi');
    }
}

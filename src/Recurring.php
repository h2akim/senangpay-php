<?php

declare(strict_types=1);

namespace SenangPay;

class Recurring extends BaseClient
{
    protected $apiEndpoint = 'https://api.senangpay.my/recurring';

    protected $sandboxEndpoint = 'https://api.sandbox.senangpay.my/recurring';

    public function product(?string $version = null): Services\Recurring\Contracts\Product
    {
        return $this->uses('Product', $version);
    }

    protected function getResourceNamespace(): string
    {
        return sprintf('%s\%s', __NAMESPACE__, 'Services\\Recurring');
    }
}

<?php

declare(strict_types=1);

namespace SenangPay;

class Refund extends BaseClient
{
    public function refund(?string $version = null): Services\Refund\Contracts\Refund
    {
        return $this->uses('Refund', $version);
    }

    protected function getResourceNamespace(): string
    {
        return sprintf('%s\%s', __NAMESPACE__, 'Services\\Refund');
    }
}

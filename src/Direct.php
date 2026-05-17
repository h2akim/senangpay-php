<?php

declare(strict_types=1);

namespace SenangPay;

class Direct extends BaseClient
{
    public function payment(?string $version = null): Services\Direct\Contracts\Payment
    {
        return $this->uses('Payment', $version);
    }

    public function fpxBank(?string $version = null): Services\Direct\Contracts\FpxBank
    {
        return $this->uses('FpxBank', $version);
    }

    protected function getResourceNamespace(): string
    {
        return sprintf('%s\%s', __NAMESPACE__, 'Services\\Direct');
    }
}

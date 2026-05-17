<?php

declare(strict_types=1);

namespace SenangPay;

class Payout extends BaseClient
{
    public function payout(?string $version = null): Services\Payout\Contracts\Payout
    {
        return $this->uses('Payout', $version);
    }

    protected function getResourceNamespace(): string
    {
        return sprintf('%s\%s', __NAMESPACE__, 'Services\\Payout');
    }
}

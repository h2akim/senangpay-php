<?php

declare(strict_types=1);

namespace SenangPay;

class Tokenisation extends BaseClient
{
    protected $apiEndpoint = 'https://app.senangpay.my/apiv1';

    protected $sandboxEndpoint = 'https://app.senangpay.my/apiv1';

    public function token(?string $version = null): Services\Tokenisation\Contracts\Token
    {
        return $this->uses('Token', $version);
    }

    protected function getResourceNamespace(): string
    {
        return sprintf('%s\%s', __NAMESPACE__, 'Services\\Tokenisation');
    }
}

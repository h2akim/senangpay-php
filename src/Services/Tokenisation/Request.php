<?php

declare(strict_types=1);

namespace SenangPay\Services\Tokenisation;

use SenangPay\Services\Common\BaseRequest;

/**
 * @property \SenangPay\Tokenisation $client
 */
class Request extends BaseRequest
{
    protected function getApiHeaders(): array
    {
        return [
            'Authorization' => 'Basic ' . base64_encode($this->client->getMerchantId() . ':'),
        ];
    }
}

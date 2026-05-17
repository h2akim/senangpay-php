<?php

declare(strict_types=1);

namespace SenangPay\Services\Refund;

use SenangPay\Services\Common\BaseRequest;

/**
 * @property \SenangPay\Refund $client
 */
class Request extends BaseRequest
{
    protected function getApiHeaders(): array
    {
        return [
            'Authorization' => $this->client->getMerchantId(),
        ];
    }
}

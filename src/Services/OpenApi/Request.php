<?php

declare(strict_types=1);

namespace SenangPay\Services\OpenApi;

use SenangPay\Services\Common\BaseRequest;

/**
 * @property \SenangPay\OpenApi $client
 */
class Request extends BaseRequest
{
    protected function getApiHeaders(): array
    {
        return [];
    }
}

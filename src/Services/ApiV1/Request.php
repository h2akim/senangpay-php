<?php

declare(strict_types=1);

namespace SenangPay\Services\ApiV1;

use SenangPay\Services\Common\BaseRequest;

/**
 * @property \SenangPay\ApiV1 $client
 */
class Request extends BaseRequest
{
    protected function getApiHeaders(): array
    {
        return [];
    }
}

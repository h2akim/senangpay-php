<?php

declare(strict_types=1);

namespace SenangPay\Services\Direct\V1;

use Laravie\Codex\Concerns\Request\Json;
use Laravie\Codex\Contracts\Response;
use SenangPay\Services\Common\Hasher;
use SenangPay\Services\Direct\Request;

class Payment extends Request implements \SenangPay\Services\Direct\Contracts\Payment
{
    use Json;

    protected $version = 'v1';

    public function createSession(array $params): Response
    {
        $hash = Hasher::hash(
            $this->client->getSecretKey()
                . ($params['customer_name'] ?? '')
                . ($params['customer_email'] ?? '')
                . ($params['customer_phone'] ?? '')
                . ($params['order_id'] ?? '')
                . ($params['amount'] ?? '')
                . ($params['detail'] ?? '')
                . ($params['payment_method'] ?? ''),
            $this->client->getSecretKey(),
            $this->client->getHashType()
        );

        $params['hash'] = $hash;

        return $this->sendJson('POST', 'payment/v3', $this->getApiHeaders(), $params);
    }
}

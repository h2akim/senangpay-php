<?php

declare(strict_types=1);

namespace SenangPay\Services\Payout\V1;

use Laravie\Codex\Concerns\Request\Json;
use Laravie\Codex\Contracts\Response;
use SenangPay\Services\Payout\Checksum;
use SenangPay\Services\Payout\Request;

class Payout extends Request implements \SenangPay\Services\Payout\Contracts\Payout
{
    use Json;

    protected $version = 'v1';

    public function submit(array $data, ?string $callbackUrl = null, ?string $orderId = null): Response
    {
        $timestamp = time();

        $body = [
            'hash' => Checksum::create($this->client->getSecretKey(), $this->client->getMerchantId(), $timestamp),
            'timestamp' => $timestamp,
            'data' => $data,
        ];

        if ($callbackUrl !== null) {
            $body['callback_url'] = $callbackUrl;
        }

        if ($orderId !== null) {
            $body['order_id'] = $orderId;
        }

        return $this->sendJson('POST', 'payout_api', $this->getApiHeaders(), $body);
    }
}

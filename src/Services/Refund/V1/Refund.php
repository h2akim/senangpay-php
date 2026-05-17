<?php

declare(strict_types=1);

namespace SenangPay\Services\Refund\V1;

use Laravie\Codex\Concerns\Request\Json;
use Laravie\Codex\Contracts\Response;
use SenangPay\Services\Refund\Request;

class Refund extends Request implements \SenangPay\Services\Refund\Contracts\Refund
{
    use Json;

    protected $version = 'v1';

    public function create(string $transactionReference, string $refundAmount): Response
    {
        $tokenResponse = $this->sendJson('GET', 'merchant/get_token', [
            'Authorization' => $this->client->getMerchantId(),
        ]);

        $tokenData = $tokenResponse->toArray();
        $token = $tokenData['data']['token'] ?? '';

        return $this->sendJson('POST', 'merchant_service/create_refund_request', [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ], [
            'transaction_reference' => $transactionReference,
            'refund_amount' => $refundAmount,
        ]);
    }
}

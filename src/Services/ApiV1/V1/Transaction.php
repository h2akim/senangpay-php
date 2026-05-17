<?php

declare(strict_types=1);

namespace SenangPay\Services\ApiV1\V1;

use Laravie\Codex\Concerns\Request\Json;
use Laravie\Codex\Contracts\Response;
use SenangPay\Services\ApiV1\Request;
use SenangPay\Services\Common\Hasher;

class Transaction extends Request implements \SenangPay\Services\ApiV1\Contracts\Transaction
{
    use Json;

    protected $version = 'v1';

    public function status(string $transactionReference): Response
    {
        $secretKey = $this->client->getSecretKey();
        $hashType = $this->client->getHashType();
        $merchantId = $this->client->getMerchantId();

        $hash = Hasher::hash(
            $merchantId . $secretKey . $transactionReference,
            $secretKey,
            $hashType
        );

        $path = 'query_transaction_status?' . http_build_query([
            'merchant_id' => $merchantId,
            'transaction_reference' => $transactionReference,
            'hash' => $hash,
        ]);

        return $this->sendJson('GET', $path, $this->getApiHeaders());
    }
}

<?php

declare(strict_types=1);

namespace SenangPay\Services\ApiV1\V1;

use Laravie\Codex\Concerns\Request\Json;
use Laravie\Codex\Contracts\Response;
use SenangPay\Services\ApiV1\Request;
use SenangPay\Services\Common\Hasher;

class Transactions extends Request implements \SenangPay\Services\ApiV1\Contracts\Transactions
{
    use Json;

    protected $version = 'v1';

    public function list(int $timestampStart, int $timestampEnd): Response
    {
        $secretKey = $this->client->getSecretKey();
        $hashType = $this->client->getHashType();
        $merchantId = $this->client->getMerchantId();

        $hash = Hasher::hash(
            $merchantId . $secretKey . $timestampStart . $timestampEnd,
            $secretKey,
            $hashType
        );

        $path = 'get_transaction_list?' . http_build_query([
            'merchant_id' => $merchantId,
            'timestamp_start' => $timestampStart,
            'timestamp_end' => $timestampEnd,
            'hash' => $hash,
        ]);

        return $this->sendJson('GET', $path, $this->getApiHeaders());
    }
}

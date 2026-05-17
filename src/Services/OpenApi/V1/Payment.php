<?php

declare(strict_types=1);

namespace SenangPay\Services\OpenApi\V1;

use SenangPay\Services\Common\Hasher;
use SenangPay\Services\OpenApi\Request;

class Payment extends Request implements \SenangPay\Services\OpenApi\Contracts\Payment
{
    protected $version = 'v1';

    public function createUrl(string $detail, string $amount, string $orderId, array $options = []): string
    {
        $secretKey = $this->client->getSecretKey();
        $hashType = $this->client->getHashType();

        $hash = Hasher::hash(
            $secretKey . $detail . $amount . $orderId,
            $secretKey,
            $hashType
        );

        $params = array_merge([
            'detail' => $detail,
            'amount' => $amount,
            'order_id' => $orderId,
            'hash' => $hash,
        ], $options);

        return sprintf(
            '%s/payment/%s?%s',
            $this->client->getApiEndpoint(),
            $this->client->getMerchantId(),
            http_build_query($params)
        );
    }

    public function verifyCallback(array $data): array
    {
        $secretKey = $this->client->getSecretKey();
        $hashType = $this->client->getHashType();

        $statusId = urldecode($data['status_id']);
        $orderId = urldecode($data['order_id']);
        $msg = urldecode($data['msg']);
        $transactionId = urldecode($data['transaction_id']);
        $hash = urldecode($data['hash']);

        $expected = Hasher::hash(
            $secretKey . $statusId . $orderId . $transactionId . $msg,
            $secretKey,
            $hashType
        );

        if (! hash_equals($expected, $hash)) {
            return [];
        }

        unset($data['hash']);
        return $data;
    }
}

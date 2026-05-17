<?php

declare(strict_types=1);

namespace SenangPay\Services\Tokenisation\V1;

use Laravie\Codex\Concerns\Request\Json;
use Laravie\Codex\Contracts\Response;
use SenangPay\Services\Common\Hasher;
use SenangPay\Services\Tokenisation\Request;

class Token extends Request implements \SenangPay\Services\Tokenisation\Contracts\Token
{
    use Json;

    protected $version = 'v1';

    public function pay(array $params): Response
    {
        $hash = Hasher::hash(
            $this->client->getMerchantId()
                . ($params['name'] ?? '')
                . ($params['email'] ?? '')
                . ($params['phone'] ?? '')
                . ($params['detail'] ?? '')
                . ($params['order_id'] ?? '')
                . ($params['amount'] ?? ''),
            $this->client->getSecretKey(),
            $this->client->getHashType()
        );

        $params['hash'] = $hash;

        return $this->sendJson('POST', 'pay_cc', $this->getApiHeaders(), $params);
    }

    public function updateStatus(string $token): Response
    {
        return $this->sendJson('POST', 'update_token_status', $this->getApiHeaders(), [
            'token' => $token,
        ]);
    }

    public function validate(string $token): Response
    {
        return $this->sendJson('POST', 'validate_token', $this->getApiHeaders(), [
            'token' => $token,
        ]);
    }
}

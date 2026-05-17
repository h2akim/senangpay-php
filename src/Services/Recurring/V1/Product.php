<?php

declare(strict_types=1);

namespace SenangPay\Services\Recurring\V1;

use Laravie\Codex\Concerns\Request\Json;
use Laravie\Codex\Contracts\Response;
use SenangPay\Services\Common\Hasher;
use SenangPay\Services\Recurring\Request;

class Product extends Request implements \SenangPay\Services\Recurring\Contracts\Product
{
    use Json;

    protected $version = 'v1';

    public function create(array $params): Response
    {
        $hash = Hasher::hash(
            $this->client->getSecretKey()
                . ($params['name'] ?? '')
                . ($params['price'] ?? '')
                . ($params['code'] ?? ''),
            $this->client->getSecretKey(),
            $this->client->getHashType()
        );

        $params['hash'] = $hash;

        return $this->sendJson('POST', 'product/create', $this->getApiHeaders(), $params);
    }
}

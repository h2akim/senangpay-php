<?php

declare(strict_types=1);

namespace SenangPay\Services\Direct\V1;

use Laravie\Codex\Concerns\Request\Json;
use Laravie\Codex\Contracts\Response;
use SenangPay\Services\Direct\Request;

class FpxBank extends Request implements \SenangPay\Services\Direct\Contracts\FpxBank
{
    use Json;

    protected $version = 'v1';

    public function list(): Response
    {
        return $this->sendJson('GET', 'fpx_bank_list', $this->getApiHeaders());
    }
}

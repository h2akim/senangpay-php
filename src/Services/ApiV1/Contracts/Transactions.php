<?php

declare(strict_types=1);

namespace SenangPay\Services\ApiV1\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Transactions extends Request
{
    public function list(int $timestampStart, int $timestampEnd): Response;
}

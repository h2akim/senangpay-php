<?php

declare(strict_types=1);

namespace SenangPay\Services\Refund\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Refund extends Request
{
    public function create(string $transactionReference, string $refundAmount): Response;
}

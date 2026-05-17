<?php

declare(strict_types=1);

namespace SenangPay\Services\Payout\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Payout extends Request
{
    public function submit(array $data, ?string $callbackUrl = null, ?string $orderId = null): Response;
}

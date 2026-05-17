<?php

declare(strict_types=1);

namespace SenangPay\Services\ApiV1\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Order extends Request
{
    public function paymentStatus(string $orderId): Response;
}

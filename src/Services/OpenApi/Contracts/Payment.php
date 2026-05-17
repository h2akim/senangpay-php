<?php

declare(strict_types=1);

namespace SenangPay\Services\OpenApi\Contracts;

use Laravie\Codex\Contracts\Request;

interface Payment extends Request
{
    public function createUrl(string $detail, string $amount, string $orderId, array $options = []): string;

    public function verifyCallback(array $data): array;
}

<?php

declare(strict_types=1);

namespace SenangPay\Services\Recurring\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Product extends Request
{
    public function create(array $params): Response;
}

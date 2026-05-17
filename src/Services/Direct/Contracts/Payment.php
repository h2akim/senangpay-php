<?php

declare(strict_types=1);

namespace SenangPay\Services\Direct\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Payment extends Request
{
    public function createSession(array $params): Response;
}

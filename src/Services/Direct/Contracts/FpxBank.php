<?php

declare(strict_types=1);

namespace SenangPay\Services\Direct\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface FpxBank extends Request
{
    public function list(): Response;
}

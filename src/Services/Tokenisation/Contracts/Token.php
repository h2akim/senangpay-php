<?php

declare(strict_types=1);

namespace SenangPay\Services\Tokenisation\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Token extends Request
{
    public function pay(array $params): Response;

    public function updateStatus(string $token): Response;

    public function validate(string $token): Response;
}

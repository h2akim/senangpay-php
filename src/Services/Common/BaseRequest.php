<?php

declare(strict_types=1);

namespace SenangPay\Services\Common;

use Laravie\Codex\Contracts\Filterable;
use Laravie\Codex\Contracts\Response as ContractsResponse;
use Laravie\Codex\Filter\Sanitizer;
use Laravie\Codex\Filter\WithSanitizer;
use Psr\Http\Message\ResponseInterface;

abstract class BaseRequest extends \Laravie\Codex\Request implements Filterable
{
    use WithSanitizer;

    protected function responseWith(ResponseInterface $message): ContractsResponse
    {
        return new BaseResponse($message);
    }

    protected function sanitizeWith(): Sanitizer
    {
        return new Sanitizer();
    }
}

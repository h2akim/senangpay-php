<?php

declare(strict_types=1);

namespace SenangPay;

use Http\Client\Common\HttpMethodsClientInterface;
use Laravie\Codex\Discovery;

class Client
{
    public static function makeOpenApi(Config $config, ?HttpMethodsClientInterface $httpClient = null): OpenApi
    {
        return new OpenApi($httpClient ?? Discovery::client(), $config);
    }

    public static function makeApiV1(Config $config, ?HttpMethodsClientInterface $httpClient = null): ApiV1
    {
        return new ApiV1($httpClient ?? Discovery::client(), $config);
    }

    public static function makeDirect(Config $config, ?HttpMethodsClientInterface $httpClient = null): Direct
    {
        return new Direct($httpClient ?? Discovery::client(), $config);
    }

    public static function makeRefund(Config $config, ?HttpMethodsClientInterface $httpClient = null): Refund
    {
        return new Refund($httpClient ?? Discovery::client(), $config);
    }

    public static function makePayout(Config $config, ?HttpMethodsClientInterface $httpClient = null): Payout
    {
        return new Payout($httpClient ?? Discovery::client(), $config);
    }

    public static function makeTokenisation(Config $config, ?HttpMethodsClientInterface $httpClient = null): Tokenisation
    {
        return new Tokenisation($httpClient ?? Discovery::client(), $config);
    }

    public static function makeRecurring(Config $config, ?HttpMethodsClientInterface $httpClient = null): Recurring
    {
        return new Recurring($httpClient ?? Discovery::client(), $config);
    }
}

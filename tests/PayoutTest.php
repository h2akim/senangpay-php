<?php

declare(strict_types=1);

namespace SenangPay\Tests;

use SenangPay\Client;
use SenangPay\Config;
use SenangPay\Tests\Support\FakeHttpMethodsClient;
use PHPUnit\Framework\TestCase;

class PayoutTest extends TestCase
{
    public function test_send_payout_basic_auth_and_hash(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456');
        $payout = Client::makePayout($config, $http);

        $payout->payout()->submit([
            ['Name' => 'John', 'Email' => 'john@test.com', 'Bank Name' => 'Maybank', 'eBanker Code' => 'MBBEMYKL', 'Account Number' => '1234535647', 'Account Type' => 1, 'Amount' => 120],
        ]);

        $call = $http->lastCall();

        $this->assertSame('POST', $call['method']);
        $this->assertStringContainsString('https://api.senangpay.my/payout_api', (string) $call['uri']);

        $expectedAuth = 'Basic ' . base64_encode('merchant-123:');
        $this->assertSame($expectedAuth, $call['headers']['Authorization']);

        $body = json_decode($call['body'], true);
        $this->assertArrayHasKey('hash', $body);
        $this->assertArrayHasKey('timestamp', $body);
        $this->assertArrayHasKey('data', $body);
        $this->assertCount(1, $body['data']);
        $this->assertSame('John', $body['data'][0]['Name']);
    }

    public function test_send_payout_with_optional_params(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456');
        $payout = Client::makePayout($config, $http);

        $payout->payout()->submit([], 'https://myweb.com/payout', 'order-123');

        $call = $http->lastCall();
        $body = json_decode($call['body'], true);

        $this->assertSame('https://myweb.com/payout', $body['callback_url']);
        $this->assertSame('order-123', $body['order_id']);
    }

    public function test_sandbox_endpoint(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456')->withSandbox();
        $payout = Client::makePayout($config, $http);

        $payout->payout()->submit([]);

        $call = $http->lastCall();
        $uri = (string) $call['uri'];

        $this->assertStringContainsString('https://api.sandbox.senangpay.my', $uri);
    }
}

<?php

declare(strict_types=1);

namespace SenangPay\Tests;

use SenangPay\Client;
use SenangPay\Config;
use SenangPay\Tests\Support\FakeHttpMethodsClient;
use PHPUnit\Framework\TestCase;

class RecurringTest extends TestCase
{
    public function test_create_product_sends_post_with_basic_auth_and_hash(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456', 'sha256');
        $recurring = Client::makeRecurring($config, $http);

        $recurring->product()->create([
            'name' => 'Monthly Plan',
            'price' => '49.99',
            'code' => 'PLAN-001',
            'description' => 'Monthly subscription',
            'sst' => 0,
            'display_address' => 0,
            'recurring_type' => 'SUBSCRIPTION',
            'frequency' => 1,
        ]);

        $call = $http->lastCall();

        $this->assertSame('POST', $call['method']);
        $this->assertStringContainsString('https://api.senangpay.my/recurring/product/create', (string) $call['uri']);

        $expectedAuth = 'Basic ' . base64_encode('merchant-123:');
        $this->assertSame($expectedAuth, $call['headers']['Authorization']);

        $body = json_decode($call['body'], true);
        $this->assertSame('Monthly Plan', $body['name']);
        $this->assertSame('PLAN-001', $body['code']);
        $this->assertArrayHasKey('hash', $body);
    }

    public function test_sandbox_endpoint(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456')->withSandbox();
        $recurring = Client::makeRecurring($config, $http);

        $recurring->product()->create([
            'name' => 'Plan',
            'price' => '10.00',
            'code' => 'P-001',
            'description' => 'Test',
            'sst' => 0,
            'display_address' => 0,
            'recurring_type' => 'SUBSCRIPTION',
            'frequency' => 1,
        ]);

        $call = $http->lastCall();
        $uri = (string) $call['uri'];

        $this->assertStringContainsString('https://api.sandbox.senangpay.my/recurring', $uri);
    }
}

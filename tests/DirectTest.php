<?php

declare(strict_types=1);

namespace SenangPay\Tests;

use SenangPay\Client;
use SenangPay\Config;
use SenangPay\Tests\Support\FakeHttpMethodsClient;
use PHPUnit\Framework\TestCase;

class DirectTest extends TestCase
{
    public function test_client_session_sends_post_with_basic_auth_and_hash(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456', 'sha256');
        $direct = Client::makeDirect($config, $http);

        $direct->payment()->createSession([
            'payment_method' => 'fpx',
            'customer_name' => 'Mohd Aiman',
            'customer_email' => 'aiman@test.com',
            'customer_phone' => '0193339922',
            'order_id' => 'order-1',
            'amount' => '200',
            'detail' => 'Payment for order-1',
        ]);

        $call = $http->lastCall();

        $this->assertSame('POST', $call['method']);
        $this->assertStringContainsString('https://api.senangpay.my/payment/v3', (string) $call['uri']);

        $expectedAuth = 'Basic ' . base64_encode('merchant-123:');
        $this->assertSame($expectedAuth, $call['headers']['Authorization']);
        $this->assertSame('application/json', $call['headers']['Content-Type']);

        $body = json_decode($call['body'], true);
        $this->assertSame('fpx', $body['payment_method']);
        $this->assertSame('Mohd Aiman', $body['customer_name']);
        $this->assertArrayHasKey('hash', $body);
    }

    public function test_fpx_bank_list_sends_get_with_basic_auth(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456', 'sha256');
        $direct = Client::makeDirect($config, $http);

        $direct->fpxBank()->list();

        $call = $http->lastCall();

        $this->assertSame('GET', $call['method']);
        $this->assertStringContainsString('https://api.senangpay.my/fpx_bank_list', (string) $call['uri']);

        $expectedAuth = 'Basic ' . base64_encode('merchant-123:');
        $this->assertSame($expectedAuth, $call['headers']['Authorization']);
    }

    public function test_sandbox_endpoint(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456')->withSandbox();
        $direct = Client::makeDirect($config, $http);

        $direct->fpxBank()->list();

        $call = $http->lastCall();
        $uri = (string) $call['uri'];

        $this->assertStringContainsString('https://api.sandbox.senangpay.my', $uri);
    }
}

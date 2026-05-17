<?php

declare(strict_types=1);

namespace SenangPay\Tests;

use SenangPay\Client;
use SenangPay\Config;
use SenangPay\Tests\Support\FakeHttpMethodsClient;
use PHPUnit\Framework\TestCase;

class TokenisationTest extends TestCase
{
    public function test_pay_sends_post_with_basic_auth_and_hash(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456', 'sha256');
        $tokenisation = Client::makeTokenisation($config, $http);

        $tokenisation->token()->pay([
            'name' => 'Abu',
            'email' => 'abu@test.com',
            'phone' => '0109876543',
            'detail' => 'Order #123',
            'order_id' => '123',
            'amount' => '1000',
            'token' => 'tok-xxx',
        ]);

        $call = $http->lastCall();

        $this->assertSame('POST', $call['method']);
        $this->assertStringContainsString('https://app.senangpay.my/apiv1/pay_cc', (string) $call['uri']);

        $expectedAuth = 'Basic ' . base64_encode('merchant-123:');
        $this->assertSame($expectedAuth, $call['headers']['Authorization']);

        $body = json_decode($call['body'], true);
        $this->assertSame('Abu', $body['name']);
        $this->assertSame('tok-xxx', $body['token']);
        $this->assertArrayHasKey('hash', $body);
    }

    public function test_update_status_sends_post(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456');
        $tokenisation = Client::makeTokenisation($config, $http);

        $tokenisation->token()->updateStatus('tok-xxx');

        $call = $http->lastCall();

        $this->assertSame('POST', $call['method']);
        $this->assertStringContainsString('update_token_status', (string) $call['uri']);

        $body = json_decode($call['body'], true);
        $this->assertSame('tok-xxx', $body['token']);
    }

    public function test_validate_sends_post(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456');
        $tokenisation = Client::makeTokenisation($config, $http);

        $tokenisation->token()->validate('tok-xxx');

        $call = $http->lastCall();

        $this->assertSame('POST', $call['method']);
        $this->assertStringContainsString('validate_token', (string) $call['uri']);

        $body = json_decode($call['body'], true);
        $this->assertSame('tok-xxx', $body['token']);
    }
}

<?php

declare(strict_types=1);

namespace SenangPay\Tests;

use GuzzleHttp\Psr7\Response;
use SenangPay\Client;
use SenangPay\Config;
use SenangPay\Tests\Support\FakeHttpMethodsClient;
use PHPUnit\Framework\TestCase;

class RefundTest extends TestCase
{
    public function test_create_refund_sends_token_req_then_refund_req(): void
    {
        $http = new FakeHttpMethodsClient();
        $http->appendResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'status' => 1,
            'msg' => 'Token generated successfully',
            'data' => ['token' => 'test-token-123'],
        ])));

        $config = Config::make('merchant-123', 'secret-456');
        $refund = Client::makeRefund($config, $http);

        $refund->refund()->create('txn-ref-1', '10.00');

        $this->assertCount(2, $http->allCalls());

        $calls = $http->allCalls();

        // First call: get token
        $this->assertSame('GET', $calls[0]['method']);
        $this->assertStringContainsString('merchant/get_token', (string) $calls[0]['uri']);
        $this->assertSame('merchant-123', $calls[0]['headers']['Authorization']);

        // Second call: create refund
        $this->assertSame('POST', $calls[1]['method']);
        $this->assertStringContainsString('merchant_service/create_refund_request', (string) $calls[1]['uri']);
        $this->assertSame('Bearer test-token-123', $calls[1]['headers']['Authorization']);

        $body = json_decode($calls[1]['body'], true);
        $this->assertSame('txn-ref-1', $body['transaction_reference']);
        $this->assertSame('10.00', $body['refund_amount']);
    }

    public function test_sandbox_endpoint(): void
    {
        $http = new FakeHttpMethodsClient();
        $http->appendResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'status' => 1, 'msg' => 'OK', 'data' => ['token' => 't'],
        ])));

        $config = Config::make('merchant-123', 'secret-456')->withSandbox();
        $refund = Client::makeRefund($config, $http);

        $refund->refund()->create('txn-ref-1', '10.00');

        $calls = $http->allCalls();

        $this->assertStringContainsString('https://api.sandbox.senangpay.my', (string) $calls[0]['uri']);
    }
}

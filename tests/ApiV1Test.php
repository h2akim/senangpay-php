<?php

declare(strict_types=1);

namespace SenangPay\Tests;

use SenangPay\Client;
use SenangPay\Config;
use SenangPay\Tests\Support\FakeHttpMethodsClient;
use PHPUnit\Framework\TestCase;

class ApiV1Test extends TestCase
{
    public function test_query_order_status_builds_correct_url_and_hash(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456', 'md5');
        $api = Client::makeApiV1($config, $http);

        $api->order()->paymentStatus('order-1');

        $call = $http->lastCall();

        $this->assertSame('GET', $call['method']);
        $uri = (string) $call['uri'];
        $this->assertStringContainsString('https://app.senangpay.my/apiv1/query_order_status', $uri);
        $this->assertStringContainsString('merchant_id=merchant-123', $uri);
        $this->assertStringContainsString('order_id=order-1', $uri);
        $this->assertStringContainsString('hash=', $uri);

        $expectedHash = md5('merchant-123' . 'secret-456' . 'order-1');
        $this->assertStringContainsString('hash=' . $expectedHash, $uri);
    }

    public function test_query_order_status_with_sha256_hash(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456', 'sha256');
        $api = Client::makeApiV1($config, $http);

        $api->order()->paymentStatus('order-1');

        $call = $http->lastCall();
        $uri = (string) $call['uri'];

        $expectedHash = hash_hmac('sha256', 'merchant-123' . 'secret-456' . 'order-1', 'secret-456');
        $this->assertStringContainsString('hash=' . $expectedHash, $uri);
    }

    public function test_query_transaction_status_builds_correct_url(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456', 'md5');
        $api = Client::makeApiV1($config, $http);

        $api->transaction()->status('txn-ref-1');

        $call = $http->lastCall();
        $uri = (string) $call['uri'];

        $this->assertStringContainsString('https://app.senangpay.my/apiv1/query_transaction_status', $uri);
        $this->assertStringContainsString('transaction_reference=txn-ref-1', $uri);

        $expectedHash = md5('merchant-123' . 'secret-456' . 'txn-ref-1');
        $this->assertStringContainsString('hash=' . $expectedHash, $uri);
    }

    public function test_get_transaction_list_builds_correct_url(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456', 'md5');
        $api = Client::makeApiV1($config, $http);

        $api->transactions()->list(1577808000, 1577894399);

        $call = $http->lastCall();
        $uri = (string) $call['uri'];

        $this->assertStringContainsString('https://app.senangpay.my/apiv1/get_transaction_list', $uri);
        $this->assertStringContainsString('timestamp_start=1577808000', $uri);
        $this->assertStringContainsString('timestamp_end=1577894399', $uri);

        $expectedHash = md5('merchant-123' . 'secret-456' . '1577808000' . '1577894399');
        $this->assertStringContainsString('hash=' . $expectedHash, $uri);
    }

    public function test_sandbox_endpoint(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456', 'md5')->withSandbox();
        $api = Client::makeApiV1($config, $http);

        $api->order()->paymentStatus('order-1');

        $call = $http->lastCall();
        $uri = (string) $call['uri'];

        $this->assertStringContainsString('https://sandbox.senangpay.my/apiv1', $uri);
    }
}

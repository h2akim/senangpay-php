<?php

declare(strict_types=1);

namespace SenangPay\Tests;

use SenangPay\Client;
use SenangPay\Config;
use SenangPay\Tests\Support\FakeHttpMethodsClient;
use PHPUnit\Framework\TestCase;

class OpenApiTest extends TestCase
{
    public function test_create_url_builds_correct_endpoint_and_hash(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456');
        $openApi = Client::makeOpenApi($config, $http);

        $url = $openApi->payment()->createUrl(
            'Test order',
            '25.50',
            'order-1',
            ['name' => 'John', 'email' => 'john@test.com']
        );

        $this->assertStringContainsString('https://app.senangpay.my/payment/merchant-123', $url);
        $this->assertStringContainsString('detail=Test+order', $url);
        $this->assertStringContainsString('amount=25.50', $url);
        $this->assertStringContainsString('order_id=order-1', $url);
        $this->assertStringContainsString('name=John', $url);
        $this->assertStringContainsString('email=john%40test.com', $url);
        $this->assertStringContainsString('hash=', $url);
    }

    public function test_create_url_with_md5_hash(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456', 'md5');
        $openApi = Client::makeOpenApi($config, $http);

        $url = $openApi->payment()->createUrl('Test', '10.00', 'order-1');

        $expectedHash = md5('secret-456' . 'Test' . '10.00' . 'order-1');
        $this->assertStringContainsString('hash=' . $expectedHash, $url);
    }

    public function test_create_url_with_sha256_hash(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456', 'sha256');
        $openApi = Client::makeOpenApi($config, $http);

        $url = $openApi->payment()->createUrl('Test', '10.00', 'order-1');

        $expectedHash = hash_hmac('sha256', 'secret-456' . 'Test' . '10.00' . 'order-1', 'secret-456');
        $this->assertStringContainsString('hash=' . $expectedHash, $url);
    }

    public function test_sandbox_endpoint(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456')->withSandbox();
        $openApi = Client::makeOpenApi($config, $http);

        $url = $openApi->payment()->createUrl('Test', '10.00', 'order-1');

        $this->assertStringContainsString('https://sandbox.senangpay.my/payment/', $url);
    }

    public function test_verify_callback_valid_hash(): void
    {
        $http = new FakeHttpMethodsClient();
        $secretKey = '53-784';
        $config = Config::make('merchant-123', $secretKey, 'md5');
        $openApi = Client::makeOpenApi($config, $http);

        $statusId = '1';
        $orderId = '56';
        $transactionId = '14363538840';
        $msg = 'Payment_was_successful';
        $hash = md5($secretKey . $statusId . $orderId . $transactionId . $msg);

        $data = [
            'status_id' => $statusId,
            'order_id' => $orderId,
            'transaction_id' => $transactionId,
            'msg' => $msg,
            'hash' => $hash,
        ];

        $result = $openApi->payment()->verifyCallback($data);

        $this->assertNotEmpty($result);
        $this->assertArrayNotHasKey('hash', $result);
        $this->assertSame($statusId, $result['status_id']);
    }

    public function test_verify_callback_invalid_hash(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456', 'md5');
        $openApi = Client::makeOpenApi($config, $http);

        $data = [
            'status_id' => '1',
            'order_id' => '56',
            'transaction_id' => '14363538840',
            'msg' => 'Payment_was_successful',
            'hash' => 'invalid-hash',
        ];

        $result = $openApi->payment()->verifyCallback($data);

        $this->assertEmpty($result);
    }

    public function test_custom_base_uri_is_used(): void
    {
        $http = new FakeHttpMethodsClient();
        $config = Config::make('merchant-123', 'secret-456')
            ->withBaseUri('https://custom.example.com/myapp');
        $openApi = Client::makeOpenApi($config, $http);

        $url = $openApi->payment()->createUrl('Test', '10.00', 'order-1');

        $this->assertStringContainsString('https://custom.example.com/myapp/payment/', $url);
    }
}

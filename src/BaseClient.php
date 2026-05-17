<?php

declare(strict_types=1);

namespace SenangPay;

use Http\Client\Common\HttpMethodsClientInterface as HttpClient;

abstract class BaseClient extends \Laravie\Codex\Client
{
    protected string $merchantId;

    protected string $secretKey;

    protected string $hashType;

    protected $apiEndpoint = 'https://api.senangpay.my';

    protected $sandboxEndpoint = 'https://api.sandbox.senangpay.my';

    protected $defaultVersion = 'v1';

    protected $supportedVersions = [
        'v1' => 'V1',
    ];

    public function __construct(
        HttpClient $http,
        Config $config,
    ) {
        $this->http = $http;
        $this->merchantId = $config->merchantId;
        $this->secretKey = $config->secretKey;
        $this->hashType = $config->hashType;

        if ($config->sandbox) {
            $this->apiEndpoint = $this->sandboxEndpoint;
        }

        if ($config->baseUri !== null) {
            $this->apiEndpoint = rtrim($config->baseUri, '/');
        }

        if ($config->version !== null) {
            $this->defaultVersion = $config->version;
        }
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function getHashType(): string
    {
        return $this->hashType;
    }
}

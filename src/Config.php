<?php

declare(strict_types=1);

namespace SenangPay;

final class Config
{
    public function __construct(
        public string $merchantId,
        public string $secretKey,
        public string $hashType = 'sha256',
        public ?string $baseUri = null,
        public ?string $version = null,
        public bool $sandbox = false
    ) {
    }

    public static function make(string $merchantId, string $secretKey, string $hashType = 'sha256'): self
    {
        return new self($merchantId, $secretKey, $hashType);
    }

    public function withSandbox(bool $enabled = true): self
    {
        $clone = clone $this;
        $clone->sandbox = $enabled;
        return $clone;
    }

    public function withBaseUri(string $baseUri): self
    {
        $clone = clone $this;
        $clone->baseUri = $baseUri;
        return $clone;
    }

    public function withVersion(string $version): self
    {
        $clone = clone $this;
        $clone->version = $version;
        return $clone;
    }

    public function withHashType(string $hashType): self
    {
        $clone = clone $this;
        $clone->hashType = $hashType;
        return $clone;
    }
}

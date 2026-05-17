<?php

declare(strict_types=1);

namespace SenangPay\Services\Payout;

use SenangPay\Services\Common\Hasher;

final class Checksum
{
    public static function create(string $secretKey, string $merchantId, int $timestamp): string
    {
        return Hasher::sha256($secretKey . $merchantId . $timestamp);
    }

    public static function verify(string $secretKey, string $refNumber, string $status, string $hash): bool
    {
        $expected = Hasher::hmacSha256($secretKey . $refNumber . $status, $secretKey);
        return hash_equals($expected, $hash);
    }
}

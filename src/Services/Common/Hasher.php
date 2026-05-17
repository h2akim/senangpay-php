<?php

declare(strict_types=1);

namespace SenangPay\Services\Common;

final class Hasher
{
    public static function md5(string $value): string
    {
        return md5($value);
    }

    public static function hmacSha256(string $value, string $key): string
    {
        return hash_hmac('sha256', $value, $key);
    }

    public static function sha256(string $value): string
    {
        return hash('sha256', $value);
    }

    public static function hash(string $value, string $key, string $type = 'sha256'): string
    {
        return $type === 'md5'
            ? self::md5($value)
            : self::hmacSha256($value, $key);
    }
}

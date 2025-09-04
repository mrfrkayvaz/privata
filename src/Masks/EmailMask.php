<?php

namespace Privata\Masks;

use Privata\Contracts\Mask;

class EmailMask implements Mask {
    public const LOCAL_MASK_LENGTH = 8;
    public const DOMAIN_MASK_LENGTH = 8;

    public static function mask(string $data, string $masking_character = '*'): string {
        if (!str_contains($data, '@')) {
            return str_repeat($masking_character, self::LOCAL_MASK_LENGTH);
        }

        [$local, $domain] = explode('@', $data, 2);

        $local = self::maskLocal($local, $masking_character);

        $domainParts = explode('.', $domain);
        $domainParts[0] = self::maskDomain($domainParts[0], $masking_character);
        $domain = implode('.', $domainParts);

        return $local . '@' . $domain;
    }

    protected static function maskLocal(string $part, string $masking_character): string {
        return substr($part, 0, 1) . str_repeat($masking_character, self::LOCAL_MASK_LENGTH - 1);
    }

    protected static function maskDomain(string $part, string $masking_character): string {
        return substr($part, 0, 1) . str_repeat($masking_character, self::DOMAIN_MASK_LENGTH - 1);
    }
}
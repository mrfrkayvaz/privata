<?php

namespace Privata\Masks;

use Privata\Contracts\Mask;

class PhoneMask implements Mask {
    public static function mask(string $data, string $masking_character = '*'): string {
        $digits = preg_replace('/\D+/', '', $data);

        $prefix = str_starts_with($data, '+') ? '+' : '';

        $firstTwo = substr($digits, 0, 2);

        $last = substr($digits, -1);

        $middleLength = max(strlen($digits) - 3, 0);
        $middle = str_repeat($masking_character, $middleLength);

        return $prefix . $firstTwo . $middle . $last;
    }
}

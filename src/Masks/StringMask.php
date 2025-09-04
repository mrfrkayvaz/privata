<?php

namespace Privata\Masks;

use Privata\Contracts\Mask;

class StringMask implements Mask {
    public static function mask(string $data, string $masking_character = '*'): string {
        $words = preg_split('/\s+/', $data);

        $maskedWords = array_map(function ($word) use ($masking_character) {
            $len = mb_strlen($word);

            if ($len === 1) {
                return $masking_character;
            } elseif ($len === 2) {
                return mb_substr($word, 0, 1) . $masking_character;
            } else {
                $first = mb_substr($word, 0, 1);
                $last = mb_substr($word, -1);
                $middle = str_repeat($masking_character, $len - 2);
                return $first . $middle . $last;
            }
        }, $words);

        return implode(' ', $maskedWords);
    }
}
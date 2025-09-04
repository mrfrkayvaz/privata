<?php

namespace Privata\Services;

use Privata\Contracts\Mask;

class EncryptionService {
    public static function mask(Mask $instance, string $data): string {
        $masking_character = config('privata.encryption.masking_character');
        
        return $instance::mask($data, $masking_character);
    }
}
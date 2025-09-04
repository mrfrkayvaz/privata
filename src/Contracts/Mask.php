<?php

namespace Privata\Contracts;

interface Mask {
    public static function mask(string $data): string;
}
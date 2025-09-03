<?php

namespace Privata\Contracts;

interface Encrypter
{
    /**
     * Encrypt the given value.
     *
     * @param string $value
     * @return string
     */
    public function encrypt(string $value): string;

    /**
     * Decrypt the given payload.
     *
     * @param string $payload
     * @return string
     */
    public function decrypt(string $payload): string;
}

<?php

namespace Privata\Drivers;

use Privata\Contracts\Encrypter;
use Privata\Exceptions\EncryptionException;
use Privata\Exceptions\DecryptionException;
use Privata\Exceptions\InvalidEncryptedDataException;
use Privata\Support\Errors;
use Throwable;

final class AES256 implements Encrypter {
    private const METHOD = 'aes-256-cbc';
    private const IV_LENGTH = 16;

    /**
     * Create a new AES256 encrypter instance.
     *
     * @param string $password The password to use for encryption/decryption
     */
    public function __construct(
        public string $password
    )  {}

    /**
     * Encrypt the given plaintext using AES-256-CBC.
     *
     * @param string $plaintext The text to encrypt
     * @return string The encrypted data as base64 encoded string
     * @throws EncryptionException When encryption fails
     */
    public function encrypt(string $plaintext): string
    {
        $key = $this->generateKey();
        $iv = $this->generateIV();

        $encrypted = openssl_encrypt($plaintext, self::METHOD, $key, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            throw new EncryptionException(
                message: Errors::ENCRYPTION_FAILED,
                algorithm: self::METHOD
            );
        }

        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypt the given encrypted message using AES-256-CBC.
     *
     * @param string $message The base64 encoded encrypted data
     * @return string The decrypted plaintext
     * @throws DecryptionException|InvalidEncryptedDataException When decryption fails or data format is invalid
     */
    public function decrypt(string $message): string
    {
        $key = $this->generateKey();
        $data = base64_decode($message);

        if ($data === false || strlen($data) <= self::IV_LENGTH) {
            throw new InvalidEncryptedDataException(
                message: Errors::INVALID_ENCRYPTED_DATA,
                algorithm: self::METHOD
            );
        }

        $iv = substr($data, 0, self::IV_LENGTH);
        $encrypted = substr($data, self::IV_LENGTH);

        $decrypted = openssl_decrypt($encrypted, self::METHOD, $key, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false) {
            throw new DecryptionException(
                message: Errors::DECRYPTION_FAILED,
                algorithm: self::METHOD
            );
        }

        return $decrypted;
    }

    /**
     * Generate encryption key from password using SHA256.
     *
     * @return string The generated encryption key
     */
    public function generateKey(): string {
        return hash('sha256', $this->password, true);
    }

    /**
     * Generate initialization vector (IV) for encryption.
     *
     * @return string The generated IV
     * @throws EncryptionException
     */
    public function generateIV(): string {
        try {
            return random_bytes(self::IV_LENGTH);
        } catch (Throwable $e) {
            throw new EncryptionException(
                message: Errors::FAILED_TO_GENERATE_RANDOM_IV,
                algorithm: self::METHOD,
                previous: $e
            );
        }
    }
}

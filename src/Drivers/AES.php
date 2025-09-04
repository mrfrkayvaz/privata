<?php

namespace Privata\Drivers;

use Privata\Contracts\Encrypter;
use Privata\Exceptions\EncryptionException;
use Privata\Exceptions\DecryptionException;
use Privata\Support\Errors;
use Throwable;
use InvalidArgumentException;

final class AES implements Encrypter {
    private string $cipher;
    private string $encryption_key;

    /**
     * Create a new AES256 encrypter instance.
     */
    public function __construct(array $params = []) {
        $this->setEncryptionKey($params);
        $this->setCipher($params);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setEncryptionKey(array $params): void {
        $encryption_key = $params['key'] ?? "";

        if (empty($encryption_key)) {
            throw new InvalidArgumentException(
                message: 'Encryption key is not configured. Please set PRIVATA_ENCRYPTION_KEY in your .env file.'
            );
        }

        $this->encryption_key = $encryption_key;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setCipher(array $params): void {
        $cipher = $params['cipher'] ?? '';

        if (empty($cipher)) {
            throw new InvalidArgumentException(
                message: 'Cipher is not configured. Please set PRIVATA_CIPHER in your .env file.'
            );
        }

        if (!in_array($cipher, openssl_get_cipher_methods(true))) {
            throw new InvalidArgumentException(
                message: 'Invalid cipher: $cipher'
            );
        }

        $this->cipher = $cipher;
    }

    public function getDriverName(): string {
        return class_basename(self::class);
    }

    public function getIVLength(): int {
        return openssl_cipher_iv_length($this->cipher);
    }

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

        $encrypted = openssl_encrypt($plaintext, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            throw new EncryptionException(
                message: Errors::ENCRYPTION_FAILED,
                algorithm: $this->getDriverName()
            );
        }

        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypt the given encrypted message using AES-256-CBC.
     *
     * @param string $message The base64 encoded encrypted data
     * @return string The decrypted plaintext
     * @throws DecryptionException
     */
    public function decrypt(string $message): string
    {
        $key = $this->generateKey();
        $data = base64_decode($message);

        if ($data === false || strlen($data) <= $this->getIVLength()) {
            throw new DecryptionException(
                message: 'Invalid encrypted data format',
                algorithm: $this->getDriverName()
            );
        }

        $iv = substr($data, 0, $this->getIVLength());
        $encrypted = substr($data, $this->getIVLength());

        $decrypted = openssl_decrypt($encrypted, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false) {
            throw new DecryptionException(
                message: Errors::DECRYPTION_FAILED,
                algorithm: $this->getDriverName()
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
        return hash('sha256', $this->encryption_key, true);
    }

    /**
     * Generate initialization vector (IV) for encryption.
     *
     * @return string The generated IV
     * @throws EncryptionException
     */
    public function generateIV(): string {
        try {
            return random_bytes($this->getIVLength());
        } catch (Throwable $e) {
            throw new EncryptionException(
                message: 'Failed to generate random IV',
                algorithm: $this->getDriverName(),
                previous: $e
            );
        }
    }
}

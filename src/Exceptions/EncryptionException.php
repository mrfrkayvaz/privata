<?php

namespace Privata\Exceptions;

use Exception;
use Throwable;

class EncryptionException extends Exception
{
    public function __construct(
        string $message,
        public string $algorithm,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns the algorithm used in the encryption exception.
     *
     * @return string The algorithm used.
     */
    public function getAlgorithm(): string {
        return $this->algorithm;
    }
}

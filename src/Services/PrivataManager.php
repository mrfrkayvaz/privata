<?php

namespace Privata\Services;

use Privata\Contracts\Encrypter;
use Privata\Drivers\AES;
use InvalidArgumentException;

class PrivataManager {
    protected function resolve(): Encrypter {
        $driver = $this->getDriverName();
        $params = $this->getParams();

        return match($driver) {
            'aes' => new AES($params),
            default => throw new InvalidArgumentException(
                message: "Unsupported driver [{$driver}]"
            ),
        };
    }

    public function encrypt(string $value): string {
        return $this->resolve()->encrypt($value);
    }

    public function decrypt(string $value): string|false {
        return $this->resolve()->decrypt($value);
    }

    public function getParams(): array {
        return config('privata.drivers.' . $this->getDriverName());
    }

    public function getDriverName(): string {
        return config('privata.encryption.driver');
    }
}
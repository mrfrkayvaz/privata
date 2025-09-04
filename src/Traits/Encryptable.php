<?php

namespace Privata\Traits;

use Privata\Facades\Privata;
use Privata\Masks\StringMask;
use Privata\Services\EncryptionService;

trait Encryptable {
    protected function encrypted(): array {
        return [];
    }

    protected function encryptionMasks(): array {
        return [];
    }

    protected function canDecrypt(): bool
    {
        return true;
    }

    public static function bootEncryptable(): void
    {
        static::retrieved(function ($model) {
            $model->applyGetMutators();
        });

        static::saving(function ($model) {
            $model->applySetMutators();
        });

        static::saved(function ($model) {
            $model->applyGetMutators();
        });
    }

    public function getEncryptedDataSuffix(): string {
        return config('privata.database.encrypted_data_suffix');
    }

    public function getEncryptedMaskedSuffix(): string {
        return config('privata.database.encrypted_masked_suffix');
    }

    public function getEncryptedTimestampSuffix(): string {
        return config('privata.database.encrypted_timestamp_suffix');
    }

    public function getAddMaskedValue(): bool {
        return config('privata.database.add_masked_value');
    }

    public function getHidden(): array
    {
        $hidden = parent::getHidden();

        foreach ($this->encrypted() as $attribute) {
            $encrypted_data_field = $attribute . $this->getEncryptedDataSuffix();
            $encrypted_timestamp_field = $attribute . $this->getEncryptedTimestampSuffix();

            if (!in_array($encrypted_data_field, $hidden)) {
                $hidden[] = $encrypted_data_field;
            }

            if (!in_array($encrypted_timestamp_field, $hidden)) {
                $hidden[] = $encrypted_timestamp_field;
            }
        }

        return $hidden;
    }

    protected function applyGetMutators(): void
    {
        foreach ($this->encrypted() as $attribute) {
            $this->applyGetMutator($attribute);
        }
    }

    protected function applySetMutators(): void
    {
        $encrypted_attributes = $this->encrypted();

        foreach ($encrypted_attributes as $attribute) {
            $this->applySetMutator($attribute);
        }
    }

    protected function applyGetMutator(string $attribute): void
    {
        $encrypted_data_field = $attribute . $this->getEncryptedDataSuffix();
        if (!isset($this->attributes[$encrypted_data_field])) return;

        $encrypted_value = $this->getAttribute($encrypted_data_field);
        $decrypted_value = Privata::decrypt($encrypted_value);

        $encrypted_masked_field = $attribute . $this->getEncryptedMaskedSuffix();

        $masks = $this->encryptionMasks();
        $mask = $masks[$attribute] ?? StringMask::class;
        $masked_value = EncryptionService::mask(new $mask, $decrypted_value);

        $attributes = $this->attributes;

        if ($this->getAddMaskedValue()) {
            $attributes[$encrypted_masked_field] = $masked_value;
        }

        if ($this->canDecrypt()) {
            $attributes[$attribute] = $decrypted_value;
        }else {
            $attributes[$attribute] = $masked_value;
        }

        if ($attribute != $encrypted_data_field)
            unset($attributes[$encrypted_data_field]);

        $this->setRawAttributes($attributes);
    }

    protected function applySetMutator(string $attribute): void
    {
        if (!isset($this->attributes[$attribute])) return;

        $value = $this->attributes[$attribute];
        $encrypted_value = Privata::encrypt($value);

        $encrypted_data_field = $attribute . $this->getEncryptedDataSuffix();
        $encrypted_timestamp_field = $attribute . $this->getEncryptedTimestampSuffix();

        $attributes = $this->attributes;
        $attributes[$encrypted_data_field] = $encrypted_value;
        $attributes[$encrypted_timestamp_field] = now();

        if ($attribute != $encrypted_data_field)
            unset($attributes[$attribute]);

        $this->setRawAttributes($attributes);
    }
}

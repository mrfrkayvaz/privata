<?php

namespace Privata\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Privata\Masks\EmailMask;
use Privata\Traits\Encryptable;

/**
 * @method static create(string[] $array)
 */
class UserMasked extends Model
{
    use Encryptable;

    protected $table = 'users';
    protected $guarded = [];

    protected function canDecrypt(): bool {
        return false;
    }

    protected function encrypted(): array {
        return ['email'];
    }

    protected function encryptionMasks(): array {
        return [
            'email' => EmailMask::class
        ];
    }
}
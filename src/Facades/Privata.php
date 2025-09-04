<?php

namespace Privata\Facades;

use Illuminate\Support\Facades\Facade;
use Privata\Services\PrivataManager;

/**
 * @method static string encrypt(string $value)
 * @method static string decrypt(string $value)
 * @see PrivataManager
 */
class Privata extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PrivataManager::class;
    }
}

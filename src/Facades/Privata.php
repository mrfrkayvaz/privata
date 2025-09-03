<?php

namespace Privata\Facades;

use Illuminate\Support\Facades\Facade;

class Privata extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return PrivataManager::class;
    }
}

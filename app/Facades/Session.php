<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Session
 * @package App\Facades
 */
class Session extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'azuresession';
    }
}


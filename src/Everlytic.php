<?php

namespace Emotality\Everlytic;

use Illuminate\Support\Facades\Facade;

class Everlytic extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \Emotality\Everlytic\EverlyticFacade::class;
    }
}

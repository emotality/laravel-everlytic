<?php

namespace Emotality\Everlytic;

use Illuminate\Support\Facades\Facade;

class Everlytic extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Emotality\Everlytic\EverlyticFacade::class;
    }
}

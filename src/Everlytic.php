<?php

namespace Emotality\Everlytic;

use Illuminate\Support\Facades\Facade;

class Everlytic extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return \Emotality\Everlytic\EverlyticFacade::class;
    }
}

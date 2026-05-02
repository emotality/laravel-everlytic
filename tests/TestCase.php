<?php

namespace Emotality\Everlytic\Tests;

use Emotality\Everlytic\EverlyticServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            EverlyticServiceProvider::class,
        ];
    }
}

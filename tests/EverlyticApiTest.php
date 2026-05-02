<?php

namespace Emotality\Everlytic\Tests;

use Emotality\Everlytic\EverlyticAPI;
use Emotality\Everlytic\EverlyticException;

class EverlyticApiTest extends TestCase
{
    public function test_has_credentials_returns_false_without_emitting_deprecations(): void
    {
        $deprecations = [];

        set_error_handler(static function (int $severity, string $message) use (&$deprecations): bool {
            if ($severity === E_DEPRECATED) {
                $deprecations[] = $message;

                return true;
            }

            return false;
        });

        try {
            $this->assertFalse($this->app->make(EverlyticAPI::class)->hasCredentials());
        } finally {
            restore_error_handler();
        }

        $this->assertSame([], $deprecations);
    }

    public function test_run_checks_throws_when_credentials_are_missing(): void
    {
        $this->expectException(EverlyticException::class);
        $this->expectExceptionMessage('Your Everlytic username, password and URL is required!');

        $this->app->make(EverlyticAPI::class)->runChecks();
    }
}

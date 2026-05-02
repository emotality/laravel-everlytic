<?php

use Emotality\Everlytic\EverlyticAPI;
use Emotality\Everlytic\EverlyticException;

test('has credentials returns false without emitting deprecations', function (): void {
    $deprecations = [];

    set_error_handler(static function (int $severity, string $message) use (&$deprecations): bool {
        if ($severity === E_DEPRECATED) {
            $deprecations[] = $message;

            return true;
        }

        return false;
    });

    try {
        expect($this->app->make(EverlyticAPI::class)->hasCredentials())->toBeFalse();
    } finally {
        restore_error_handler();
    }

    expect($deprecations)->toBe([]);
});

test('run checks throws when credentials are missing', function (): void {
    $this->expectException(EverlyticException::class);
    $this->expectExceptionMessage('Your Everlytic username, password and URL is required!');

    $this->app->make(EverlyticAPI::class)->runChecks();
});

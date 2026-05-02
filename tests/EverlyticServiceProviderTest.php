<?php

use Emotality\Everlytic\EverlyticAPI;
use Emotality\Everlytic\EverlyticMailTransport;

test('it registers the api as a singleton', function (): void {
    expect($this->app->make(EverlyticAPI::class))
        ->toBe($this->app->make(EverlyticAPI::class));
});

test('it registers the everlytic mail transport', function (): void {
    $transport = $this->app['mail.manager']->createSymfonyTransport([
        'transport' => 'everlytic',
    ]);

    expect($transport)
        ->toBeInstanceOf(EverlyticMailTransport::class)
        ->and((string) $transport)
        ->toBe('everlytic');
});

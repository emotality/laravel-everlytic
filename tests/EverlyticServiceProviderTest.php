<?php

namespace Emotality\Everlytic\Tests;

use Emotality\Everlytic\EverlyticAPI;
use Emotality\Everlytic\EverlyticMailTransport;

class EverlyticServiceProviderTest extends TestCase
{
    public function test_it_registers_the_api_as_a_singleton(): void
    {
        $this->assertSame(
            $this->app->make(EverlyticAPI::class),
            $this->app->make(EverlyticAPI::class),
        );
    }

    public function test_it_registers_the_everlytic_mail_transport(): void
    {
        $transport = $this->app['mail.manager']->createSymfonyTransport([
            'transport' => 'everlytic',
        ]);

        $this->assertInstanceOf(EverlyticMailTransport::class, $transport);
        $this->assertSame('everlytic', (string) $transport);
    }
}

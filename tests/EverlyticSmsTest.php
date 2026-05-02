<?php

namespace Emotality\Everlytic\Tests;

use Emotality\Everlytic\EverlyticException;
use Emotality\Everlytic\EverlyticSms;

class EverlyticSmsTest extends TestCase
{
    public function test_it_can_be_constructed_without_arguments(): void
    {
        $sms = new EverlyticSms;

        $this->expectException(EverlyticException::class);
        $this->expectExceptionMessage('SMS has no recipient(s) attached.');

        $sms->send();
    }

    public function test_it_rejects_empty_messages(): void
    {
        $sms = new EverlyticSms('+27820000001');

        $this->expectException(EverlyticException::class);
        $this->expectExceptionMessage('SMS message can\'t be empty.');

        $sms->send();
    }
}

<?php

use Emotality\Everlytic\EverlyticException;
use Emotality\Everlytic\EverlyticSms;

test('it can be constructed without arguments', function (): void {
    $sms = new EverlyticSms;

    $this->expectException(EverlyticException::class);
    $this->expectExceptionMessage('SMS has no recipient(s) attached.');

    $sms->send();
});

test('it rejects empty messages', function (): void {
    $sms = new EverlyticSms('+27820000001');

    $this->expectException(EverlyticException::class);
    $this->expectExceptionMessage('SMS message can\'t be empty.');

    $sms->send();
});

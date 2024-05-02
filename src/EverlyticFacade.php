<?php

namespace Emotality\Everlytic;

use Illuminate\Support\Facades\App;
use Symfony\Component\Mailer\SentMessage;

class EverlyticFacade
{
    /**
     * Everlytic API class.
     *
     * @return \Emotality\Everlytic\EverlyticAPI
     */
    private static function api()
    {
        return App::get(EverlyticAPI::class);
    }

    /**
     * Send SMS to a single recipient.
     *
     * @throws \Emotality\Everlytic\EverlyticException
     */
    public static function sms(string $recipient, string $message): bool
    {
        return self::api()->sendSms($recipient, $message);
    }

    /**
     * Send SMS to multiple recipients.
     *
     * @return array<string, bool>
     * @throws \Emotality\Everlytic\EverlyticException
     */
    public static function smsMany(array $recipients, string $message): array
    {
        $response = [];

        foreach (array_unique($recipients) as $recipient) {
            $response[$recipient] = self::api()->sendSms(strval($recipient), $message);
        }

        return $response;
    }

    /**
     * Send an email.
     *
     * @throws \Emotality\Everlytic\EverlyticException
     */
    public static function email(SentMessage $message): void
    {
        self::api()->sendEmail($message);
    }
}

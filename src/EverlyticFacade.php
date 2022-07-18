<?php

namespace Emotality\Everlytic;

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
        return app(EverlyticAPI::class);
    }

    /**
     * Send SMS to a single recipient.
     *
     * @param  string  $recipient
     * @param  string  $message
     * @return bool
     * @throws \Emotality\Everlytic\EverlyticException
     */
    public static function sms(string $recipient, string $message) : bool
    {
        return self::api()->sendSms($recipient, $message);
    }

    /**
     * Send SMS to multiple recipients.
     *
     * @param  array  $recipients
     * @param  string  $message
     * @return array<string, bool>
     * @throws \Emotality\Everlytic\EverlyticException
     */
    public static function smsMany(array $recipients, string $message) : array
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
     * @param  \Symfony\Component\Mailer\SentMessage  $message
     * @return void
     * @throws \Emotality\Everlytic\EverlyticException
     */
    public static function email(SentMessage $message) : void
    {
        self::api()->sendEmail($message);
    }
}

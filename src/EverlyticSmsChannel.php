<?php

namespace Emotality\Everlytic;

use Illuminate\Notifications\Notification;

class EverlyticSmsChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @throws \Emotality\Everlytic\EverlyticException
     */
    public function send($notifiable, Notification $notification): void
    {
        if (method_exists($notification, 'toEverlytic')) {
            $notification->toEverlytic($notifiable)->send();
        } elseif (method_exists($notification, 'toSms')) {
            $notification->toSms($notifiable)->send();
        } elseif (method_exists($notification, 'sms')) {
            $notification->sms($notifiable)->send();
        } else {
            throw new EverlyticException('toSms() function not found in Notification to send SMS.');
        }
    }
}

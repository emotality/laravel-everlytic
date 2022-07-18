<?php

namespace Emotality\Everlytic;

use Illuminate\Notifications\Notification;

class EverlyticSmsChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
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
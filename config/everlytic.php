<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Everlytic Credentials
    |--------------------------------------------------------------------------
    |
    | Your Everlytic URL and credentials.
    |
    */
    'url' => env('EVERLYTIC_URL'),

    'username' => env('EVERLYTIC_USERNAME'),

    'password' => env('EVERLYTIC_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Email Options
    |--------------------------------------------------------------------------
    |
    | @see https://help.everlytic.com/knowledgebase/send-a-transactional-email/
    |
    */
    'mail_options' => [
        // Whether to track if user opens the email.
        'track_opens' => true, // Default: true

        // Whether to track if user click on links.
        'track_links' => true, // Default: true

        // If set to "true" transactions will be queued for sending instead of sending each one immediately.
        'batch_send' => false, // Default: false
    ],

    /*
    |--------------------------------------------------------------------------
    | Exceptions
    |--------------------------------------------------------------------------
    |
    | If these are set to true, response errors from Everlytic API will throw
    | exceptions instead of just logging them silently.
    |
    */
    'exceptions' => [
        // Note: If you are using queues and exceptions are disabled, the queue
        // system will see it as a successful send.
        'mail' => true, // Default: true

        // Note: If you send an SMS to multiple recipients and exceptions are
        // enabled, the rest of the recipients will not receive their SMS.
        'sms' => false, // Default: false
    ],

];

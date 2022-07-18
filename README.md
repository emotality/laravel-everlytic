# Everlytic for Laravel

<p>
    <a href="https://packagist.org/packages/emotality/laravel-everlytic"><img src="https://img.shields.io/packagist/l/emotality/laravel-everlytic" alt="License"></a>
    <a href="https://packagist.org/packages/emotality/laravel-everlytic"><img src="https://img.shields.io/packagist/v/emotality/laravel-everlytic" alt="Latest Version"></a>
    <a href="https://packagist.org/packages/emotality/laravel-everlytic"><img src="https://img.shields.io/packagist/dt/emotality/laravel-everlytic" alt="Total Downloads"></a>
</p>

Laravel package to send transactional SMSes and emails (mail driver) via Everlytic.

<p>
    <a href="https://www.everlytic.com" target="_blank">
        <img src="https://raw.githubusercontent.com/emotality/files/master/GitHub/everlytic.png" height="39">
    </a>
</p>

## Requirements

- PHP 8.0+
- Laravel 9.0+

## Installation

1. `composer require emotality/laravel-everlytic`
2. `php artisan vendor:publish --provider="Emotality\Everlytic\EverlyticServiceProvider"`
3. Add the following lines to your `.env`:

```
EVERLYTIC_URL="https://<everlytic_domain>.everlytic.net"
EVERLYTIC_USERNAME="<everlytic_username>"
EVERLYTIC_PASSWORD="<everlytic_password>"
```

4. Add the `everlytic` block to the `mailers` array, inside your `config/mail.php` file:

```php
'mailers' => [
    ...
    'everlytic' => [
        'transport' => 'everlytic',
    ],
],
```

5. Update the `MAIL_MAILER` in your `.env`:

```
MAIL_MAILER=everlytic
```

## Usage

### Sending an email:

Just send emails like you normally would! :smile:

---

### Sending an SMS to a single recipient:

```php
\Everlytic::sms('+27820000001', "1st Line\n2nd Line\n3rd Line");
```

Response will be a `bool`, `true` if successful, `false` if unsuccessful.

---

### Sending an SMS to multiple recipients:

```php
\Everlytic::smsMany(['+27820000001', '+27820000002'], "1st Line\n2nd Line\n3rd Line");
```

Response will be an array where the keys are the recipients' numbers, the values will be booleans:

```php
array:2 [▼
  "+27820000001" => true
  "+27820000002" => false
]
```

---

### Sending an email and/or SMS via notification:

```php
namespace App\Notifications;

use Emotality\Everlytic\EverlyticSms;
use Emotality\Everlytic\EverlyticSmsChannel;
use Illuminate\Notifications\Notification;

class ExampleNotification extends Notification
{
    // Notification channels
    public function via($notifiable)
    {
        return ['mail', EverlyticSmsChannel::class];
    }
    
    // Send email
    public function toMail($notifiable)
    {
        return new \App\Mail\ExampleMail($notifiable);
    }
    
    // Send SMS
    public function toSms($notifiable) // Can also use toEverlytic($notifiable)
    {
        // Send SMS to a single recipient
        return (new EverlyticSms())
            ->to($notifiable->mobile) // Assuming $user->mobile
            ->message("1st Line\n2nd Line\n3rd Line");
            
        // or send SMS to multiple recipients
        return (new EverlyticSms())
            ->toMany(['+27820000001', '+27820000002'])
            ->message("1st Line\n2nd Line\n3rd Line");
    }
}
```

## License

laravel-everlytic is released under the MIT license. See [LICENSE](https://github.com/emotality/laravel-everlytic/blob/master/LICENSE) for details.

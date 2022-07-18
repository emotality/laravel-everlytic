# Everlytic for Laravel

<p>
    <a href="https://packagist.org/packages/emotality/laravel-everlytic"><img src="https://img.shields.io/packagist/l/emotality/laravel-everlytic" alt="License"></a>
    <a href="https://packagist.org/packages/emotality/laravel-everlytic"><img src="https://img.shields.io/packagist/v/emotality/laravel-everlytic" alt="Latest Version"></a>
    <a href="https://packagist.org/packages/emotality/laravel-everlytic"><img src="https://img.shields.io/packagist/dt/emotality/laravel-everlytic" alt="Total Downloads"></a>
</p>

Laravel package to send transactional SMSes and emails via Everlytic (Laravel mail driver).

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
EVERLYTIC_URL="https://<everlytic_url>.everlytic.net"
EVERLYTIC_USERNAME="<everlytic_username>"
EVERLYTIC_PASSWORD="<everlytic_password>"
```

4. Update the `MAIL_MAILER` in your `.env`:

```
MAIL_MAILER=everlytic
```

5. Add the `everlytic` block to the `mailers` array, inside your `config/mail.php` file:

```php
'mailers' => [
    ...
    'everlytic' => [
        'transport' => 'everlytic',
    ],
]
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

## License

laravel-everlytic is released under the MIT license. See [LICENSE](https://github.com/emotality/laravel-everlytic/blob/master/LICENSE) for details.

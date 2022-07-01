# Everlytic Mail Driver for Laravel

<p>
    <a href="https://packagist.org/packages/emotality/laravel-everlytic"><img src="https://img.shields.io/packagist/l/emotality/laravel-everlytic" alt="License"></a>
    <a href="https://packagist.org/packages/emotality/laravel-everlytic"><img src="https://img.shields.io/packagist/v/emotality/laravel-everlytic" alt="Latest Version"></a>
    <a href="https://packagist.org/packages/emotality/laravel-everlytic"><img src="https://img.shields.io/packagist/dt/emotality/laravel-everlytic" alt="Total Downloads"></a>
</p>

Laravel mail driver to send transactional emails via Everlytic.

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
2. Add the `everlytic` block to your `config/mail.php` file inside `mailers`:

```php
'mailers' => [
    ...
    'everlytic' => [
        'transport' => 'everlytic',
        'url' => env('EVERLYTIC_URL'),
        'username' => env('EVERLYTIC_USERNAME'),
        'password' => env('EVERLYTIC_PASSWORD'),
        // Optional:
        'options' => [
            'track_opens' => true,
            'track_links' => true,
            'batch_send' => false,
        ],
    ],
    ...
```

3. Add the following to your `.env` file:

```
EVERLYTIC_URL="https://example.everlytic.net"
EVERLYTIC_USERNAME=""
EVERLYTIC_PASSWORD=""
```
4. Update your mail driver in your `.env` file: 

```
MAIL_MAILER=everlytic
```

## Usage

Just send emails like you normally would! :smile:

## License

laravel-everlytic is released under the MIT license. See [LICENSE](https://github.com/emotality/laravel-everlytic/blob/master/LICENSE) for details.

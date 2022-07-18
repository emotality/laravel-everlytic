<?php

namespace Emotality\Everlytic;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\MessageConverter;

class EverlyticAPI
{
    /**
     * The Everlytic API client.
     *
     * @var \Illuminate\Http\Client\PendingRequest
     */
    protected $client;

    /**
     * The Everlytic config.
     *
     * @var array
     */
    protected $config;

    /**
     * EverlyticAPI constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = config('everlytic') ?? [];

        if ($this->hasCredentials()) {
            $this->client = \Http::withOptions([
                'base_uri'        => rtrim($this->config['url'], '/'),
                'debug'           => false,
                'verify'          => true,
                'version'         => 2.0,
                'connect_timeout' => 30,
                'timeout'         => 60,
            ])->withBasicAuth($this->config['username'], $this->config['password'])->acceptJson();
        }
    }

    /**
     * Check if credentials are set.
     *
     * @return bool
     */
    public function hasCredentials() : bool
    {
        return strlen($this->config['username'] ?? null)
            && strlen($this->config['password'] ?? null)
            && strlen($this->config['url'] ?? null);
    }

    /**
     * Run checks before sending API requests.
     *
     * @return void
     * @throws \Emotality\Everlytic\EverlyticException
     */
    public function runChecks() : void
    {
        if (! $this->hasCredentials()) {
            // Run: php artisan vendor:publish --provider="Emotality\Everlytic\EverlyticServiceProvider"
            // Add: EVERLYTIC_USERNAME=""
            // Add: EVERLYTIC_PASSWORD=""
            // Add: EVERLYTIC_URL=""
            throw new EverlyticException('Your Everlytic username, password and URL is required!');
        }
    }

    /**
     * Handle API request to send SMS(es).
     *
     * @param  string  $recipient
     * @param  string  $message
     * @return bool
     * @throws \Emotality\Everlytic\EverlyticException
     */
    public function sendSms(string $recipient, string $message) : bool
    {
        $this->runChecks();

        $recipient = str_replace('+', '', $recipient);

        $response = $this->client->post('/api/2.0/production/sms/message', [
            'mobile_number' => $recipient,
            'message'       => $message,
        ]);

        if ($response->failed()) {
            return $this->smsError($response->object()->error->message ?? sprintf('SMS to %s failed to send!', $recipient), $response->status());
        }

        return true;
    }

    /**
     * Handle API request to send an email.
     *
     * @param  \Symfony\Component\Mailer\SentMessage  $message
     * @return void
     * @throws \Emotality\Everlytic\EverlyticException
     */
    public function sendEmail(SentMessage $message) : void
    {
        $this->runChecks();

        $email = MessageConverter::toEmail($message->getOriginalMessage());

        // From
        if (count($email->getFrom())) {
            $from = $email->getFrom()[0];
            $from = [$from->getAddress() => $from->getName()];
        } else {
            $this->emailError('No FROM address attached.');
        }

        // Reply-To
        if (count($email->getReplyTo())) {
            $reply_to = $email->getReplyTo()[0];
            $reply_to = [$reply_to->getAddress() => $reply_to->getName()];
        } else {
            $reply_to = $from;
        }

        // To
        if (count($email->getTo())) {
            $to = [];

            foreach ($email->getTo() as $recipient) {
                $to[$recipient->getAddress()] = $recipient->getName();
            }
        } else {
            $this->emailError('No TO address attached.');
        }

        // CC
        if (count($email->getCc())) {
            $cc = [];

            foreach ($email->getCc() as $recipient) {
                $to[$recipient->getAddress()] = $recipient->getName();
            }
        } else {
            $cc = [];
        }

        // Subject
        if (! $subject = $email->getSubject()) {
            $this->emailError('No SUBJECT attached.');
        }

        // Body
        $body = [];

        if ($html = $email->getHtmlBody()) {
            $body['html'] = $html;
        }

        if ($text = $email->getTextBody()) {
            $body['text'] = $text;
        }

        if (empty($body)) {
            $this->emailError('No BODY attached.');
        }

        // Attachments
        if (count($email->getAttachments())) {
            $attachments = [];

            foreach ($email->getAttachments() as $att) {
                $attachments[] = [
                    'filename'   => $att->getFilename(),
                    'data'       => $att->bodyToString(),
                    'content_id' => $att->getContentId(),
                ];
            }
        } else {
            $attachments = [];
        }

        // Options
        $options = collect($this->config['mail_options'])->only(['track_opens', 'track_links', 'batch_send'])->toArray();

        foreach ($options as $option => $value) {
            if (is_bool($value) || is_int($value)) {
                $options[$option] = $value ? 'yes' : 'no';
            } elseif (is_string($value)) {
                $options[$option] = in_array(strtolower($value), ['yes', 'true', '1']) ? 'yes' : 'no';
            } else {
                unset($options[$option]);
            }
        }

        // Send request
        $response = $this->client->post('/api/2.0/trans_mails', [
            'headers'     => compact('from', 'reply_to', 'to', 'cc', 'subject'),
            'body'        => $body,
            'attachments' => $attachments,
            'options'     => $options,
        ]);

        // Handle error
        if ($response->failed()) {
            $this->emailError($response->object()->error->message ?? sprintf('Email to %s failed to send!', implode(', ', $to)), $response->status());
        }
    }

    /**
     * Throw exception or log error message.
     *
     * @param  string  $message
     * @param  int  $code
     * @return bool
     * @throws \Emotality\Everlytic\EverlyticException
     */
    private function emailError(string $message, int $code = 1337) : bool
    {
        if ($this->config['exceptions']['email'] ?? true) {
            throw new EverlyticException($message, $code);
        } else {
            \Log::critical(sprintf('Everlytic Email Error: "%s"', $message));
        }

        return false;
    }

    /**
     * Throw exception or log error message.
     *
     * @param  string  $message
     * @param  int  $code
     * @return bool
     * @throws \Emotality\Everlytic\EverlyticException
     */
    private function smsError(string $message, int $code = 1337) : bool
    {
        if ($this->config['exceptions']['sms'] ?? false) {
            throw new EverlyticException($message, $code);
        } else {
            \Log::critical(sprintf('Everlytic SMS Error: "%s"', $message));
        }

        return false;
    }
}
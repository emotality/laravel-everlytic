<?php

namespace Emotality\Everlytic;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

/**
 * @see https://help.everlytic.com/knowledgebase/send-a-transactional-email/
 */
class EverlyticMailTransport extends AbstractTransport
{
    /**
     * The Everlytic API client.
     *
     * @var \Illuminate\Http\Client\PendingRequest
     */
    protected $client;

    /**
     * The mail config.
     *
     * @var array
     */
    protected $config;

    /**
     * Create a new Everlytic transport instance.
     *
     * @param  array  $config
     * @return void
     */
    public function __construct(array $config)
    {
        parent::__construct();

        if (! strlen($config['username'] ?? null)
            || ! strlen($config['password'] ?? null)
            || ! strlen($config['url'] ?? null)) {
            throw new EverlyticException('Your Everlytic username, password and URL is required!');
        }

        $headers = [
            'Accept'        => 'application/json',
            'Authorization' => sprintf('Basic %s', base64_encode($config['username'].':'.$config['password'])),
        ];

        $this->config = $config;
        $this->client = \Http::withOptions([
            'base_uri'        => rtrim($config['url'], '/'),
            'debug'           => false,
            'verify'          => true,
            'version'         => 2.0,
            'connect_timeout' => 30,
            'timeout'         => 60,
            'headers'         => $headers,
        ]);
    }

    /**
     * @param  \Symfony\Component\Mailer\SentMessage  $message
     * @return void
     */
    protected function doSend(SentMessage $message) : void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        // From
        if (count($email->getFrom())) {
            $from = $email->getFrom()[0];
            $from = [$from->getAddress() => $from->getName()];
        } else {
            throw new EverlyticException('No FROM address attached.');
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
            throw new EverlyticException('No TO address attached.');
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
            throw new EverlyticException('No SUBJECT attached.');
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
            throw new EverlyticException('No BODY attached.');
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
        $options = isset($this->config['options'])
            ? collect($this->config['options'])->only(['track_opens', 'track_links', 'batch_send'])->toArray()
            : [];

        foreach ($options as $option => $value) {
            if (is_string($value)) {
                if (in_array(strtolower($value), ['yes', 'no'])) {
                    $options[$option] = strtolower($value);
                } elseif (in_array(strtolower($value), ['true', '1'])) {
                    $options[$option] = 'yes';
                } elseif (in_array(strtolower($value), ['false', '0'])) {
                    $options[$option] = 'no';
                }
            } elseif (is_bool($value) || is_int($value)) {
                $options[$option] = $value ? 'yes' : 'no';
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
            throw new EverlyticException($response->object()->error->message ?? 'Email failed to send!', $response->status());
        }
    }

    /**
     * Get the string representation of the transport.
     *
     * @return string
     */
    public function __toString() : string
    {
        return 'everlytic';
    }
}
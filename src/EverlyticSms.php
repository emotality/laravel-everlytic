<?php

namespace Emotality\Everlytic;

class EverlyticSms
{
    /**
     * SMS recipients.
     *
     * @var array $to
     */
    protected $to = [];

    /**
     * SMS message.
     *
     * @var string $message
     */
    protected $message;

    /**
     * EverlyticSms constructor.
     *
     * @param  string|array|null  $to
     * @param  string|null  $message
     * @return void
     */
    public function __construct($to = null, string $message = null)
    {
        if ($to) {
            $this->to = is_array($to) ? $to : [$to];
        }

        $this->message = $message;
    }

    /**
     * Add SMS recipient.
     *
     * @param  string  $to
     * @return $this
     */
    public function to(string $to)
    {
        $this->to[] = $to;

        return $this;
    }

    /**
     * Add many SMS recipients.
     *
     * @param  array  $to
     * @return $this
     */
    public function toMany(array $to)
    {
        $this->to = array_merge($this->to, $to);

        return $this;
    }

    /**
     * Set SMS body.
     *
     * @param  string  $message
     * @return $this
     */
    public function message(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Send SMS(es).
     *
     * @return void
     * @throws \Emotality\Everlytic\EverlyticException
     */
    public function send()
    {
        if (! count($this->to)) {
            throw new EverlyticException('SMS has no recipient(s) attached.');
        }

        if (! $this->message) {
            throw new EverlyticException('SMS message can\'t be empty.');
        }

        EverlyticFacade::smsMany($this->to, $this->message);
    }
}
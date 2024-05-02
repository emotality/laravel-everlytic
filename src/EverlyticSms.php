<?php

namespace Emotality\Everlytic;

class EverlyticSms
{
    /**
     * SMS recipients.
     */
    protected array $to = [];

    /**
     * SMS message.
     */
    protected string $message;

    /**
     * EverlyticSms constructor.
     *
     * @param  string|array|null  $to
     * @return void
     */
    public function __construct($to = null, ?string $message = null)
    {
        if ($to) {
            $this->to = is_array($to) ? $to : [$to];
        }

        $this->message = $message;
    }

    /**
     * Add SMS recipient.
     *
     * @return $this
     */
    public function to(string $to): self
    {
        $this->to[] = $to;

        return $this;
    }

    /**
     * Add many SMS recipients.
     *
     * @return $this
     */
    public function toMany(array $to): self
    {
        $this->to = array_merge($this->to, $to);

        return $this;
    }

    /**
     * Set SMS body.
     *
     * @return $this
     */
    public function message(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Send SMS(es).
     *
     * @throws \Emotality\Everlytic\EverlyticException
     */
    public function send(): void
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

<?php

namespace Emotality\Everlytic;

use Illuminate\Support\Facades\App;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;

/**
 * @see https://help.everlytic.com/knowledgebase/send-a-transactional-email/
 */
class EverlyticMailTransport extends AbstractTransport
{
    /**
     * Everlytic API class.
     *
     * @return \Emotality\Everlytic\EverlyticAPI
     */
    private function api()
    {
        return App::get(EverlyticAPI::class);
    }

    /**
     * Process email message.
     */
    protected function doSend(SentMessage $message): void
    {
        $this->api()->sendEmail($message);
    }

    /**
     * Get the string representation of the transport.
     */
    public function __toString(): string
    {
        return 'everlytic';
    }
}

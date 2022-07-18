<?php

namespace Emotality\Everlytic;

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
        return app(EverlyticAPI::class);
    }

    /**
     * Process email message.
     *
     * @param  \Symfony\Component\Mailer\SentMessage  $message
     * @return void
     */
    protected function doSend(SentMessage $message) : void
    {
        $this->api()->sendEmail($message);
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
<?php

namespace Api\Infrastructure\Model\EventDispatcher\Listener;

use Api\Model\User\Entity\Event\UserCreatedEvent;

class UserCreated
{
    private $mailer;
    private $from;

    public function __construct(\Swift_Mailer $mailer, array $from)
    {
        $this->mailer = $mailer;
        $this->from = $from;
    }

    public function __invoke(UserCreatedEvent $event)
    {
        $message = (new \Swift_Message('Sign Up Confirmation'))
            ->setFrom($this->from)
            ->setTo($event->getEmail()->getEmail())
            ->setBody('Token: ' . $event->getToken()->getToken());

        if (!$this->mailer->send($message)) {
            throw new \RuntimeException('Unable to send message.');
        }
    }
}
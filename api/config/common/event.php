<?php

use Api\Infrastructure\Model\EventDispatcher\Listener;
use Api\Infrastructure\Model\EventDispatcher\SyncEventDispatcher;
use Api\Model\EventDispatcher;
use Api\Model\User\Entity\Event\UserCreatedEvent;
use Psr\Container\ContainerInterface;

return [
    EventDispatcher::class => function (ContainerInterface $container) {
        return new SyncEventDispatcher(
            $container,
            [
                UserCreatedEvent::class => [
                    Listener\UserCreated::class
                ]
            ]
        );
    },

    Listener\UserCreated::class => function (ContainerInterface $container) {
        return new Listener\UserCreated(
            $container->get(Swift_Mailer::class),
            $container->get('config')['mailer']['from']
        );
    }
];
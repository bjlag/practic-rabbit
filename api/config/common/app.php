<?php

use Api\Http\Action;
use Api\Model;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return [
    'config' => [
        'auth' => [
            'confirm_token_interval' => 'PT5M',
        ]
    ],

    Model\User\Service\Flusher::class => function (ContainerInterface $container) {
        return new \Api\Infrastructure\Model\Service\DoctrineFlusher(
            $container->get(EntityManagerInterface::class)
        );
    },

    Model\User\Entity\UserRepository::class => function (ContainerInterface $container) {
        return new \Api\Infrastructure\Model\User\Entity\DoctrineUserRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    Model\User\Service\PasswordHasher::class => function (ContainerInterface $container) {
        return new \Api\Infrastructure\Model\User\Service\BCryptPasswordHasher();
    },

    Model\User\Service\ConfirmTokenizer::class => function (ContainerInterface $container) {
        return new \Api\Infrastructure\Model\User\Service\RandConfirmTokenizer(
            new DateInterval($container->get('config')['auth']['confirm_token_interval'])
        );
    },

    Model\User\UseCase\SignUp\Request\Handler::class => function (ContainerInterface $container) {
        return new Model\User\UseCase\SignUp\Request\Handler(
            $container->get(Model\User\Entity\UserRepository::class),
            $container->get(Model\User\Service\PasswordHasher::class),
            $container->get(Model\User\Service\ConfirmTokenizer::class),
            $container->get(Model\User\Service\Flusher::class)
        );
    },

    Action\Auth\SignUp\RequestAction::class => function (ContainerInterface $container) {
        return new Action\Auth\SignUp\RequestAction(
            $container->get(Model\User\UseCase\SignUp\Request\Handler::class)
        );
    }
];
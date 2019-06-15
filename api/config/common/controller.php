<?php

use Api\Http\Action;
use Api\Model;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Container\ContainerInterface;

return [
    Model\User\UseCase\SignUp\Request\Handler::class => function (ContainerInterface $container) {
        return new Model\User\UseCase\SignUp\Request\Handler(
            $container->get(Model\User\Entity\UserRepository::class),
            $container->get(Model\User\Service\PasswordHasher::class),
            $container->get(Model\User\Service\ConfirmTokenizer::class),
            $container->get(Model\User\Service\Flusher::class)
        );
    },

    Model\User\UseCase\SignUp\Confirm\Handler::class => function (ContainerInterface $container) {
        return new Model\User\UseCase\SignUp\Confirm\Handler(
            $container->get(Model\User\Entity\UserRepository::class),
            $container->get(Model\User\Service\Flusher::class)
        );
    },

    Action\Auth\SignUp\RequestAction::class => function (ContainerInterface $container) {
        return new Action\Auth\SignUp\RequestAction(
            $container->get(Model\User\UseCase\SignUp\Request\Handler::class),
            $container->get(\Api\Http\Validator\SymfonyValidator::class)
        );
    },

    Action\Auth\SignUp\ConfirmAction::class => function (ContainerInterface $container) {
        return new Action\Auth\SignUp\ConfirmAction(
            $container->get(Model\User\UseCase\SignUp\Confirm\Handler::class)
        );
    },

    Action\Auth\OAuthAction::class => function (ContainerInterface $container) {
        return new Action\Auth\OAuthAction(
            $container->get(AuthorizationServer::class)
        );
    }
];
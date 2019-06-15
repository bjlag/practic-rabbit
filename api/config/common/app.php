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

    \Symfony\Component\Validator\Validator\ValidatorInterface::class => function () {
        \Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

        return Symfony\Component\Validator\Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    },

    \Api\Http\Validator\SymfonyValidator::class => function(ContainerInterface $container) {
        return new \Api\Http\Validator\SymfonyValidator(
            $container->get(\Symfony\Component\Validator\Validator\ValidatorInterface::class)
        );
    },

    Middleware\BodyParamsMiddleware::class => function () {
        return new Middleware\BodyParamsMiddleware();
    },

    Middleware\DomainExceptionMiddleware::class => function () {
        return new Middleware\DomainExceptionMiddleware();
    },

    \Api\Http\Middleware\ValidationExceptionMiddleware::class => function () {
        return new \Api\Http\Middleware\ValidationExceptionMiddleware();
    },

    Model\User\Service\Flusher::class => function (ContainerInterface $container) {
        return new \Api\Infrastructure\Model\Service\DoctrineFlusher(
            $container->get(EntityManagerInterface::class),
            $container->get(Model\EventDispatcher::class)
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

    Model\User\UseCase\SignUp\Confirm\Handler::class => function(ContainerInterface $container) {
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
];
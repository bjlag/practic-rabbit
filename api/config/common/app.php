<?php

use Api\Http\Middleware;
use Api\Http\Validator\SymfonyValidator;
use Api\Infrastructure\Model as ModelInfrastructure;
use Api\Model;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

return [
    'config' => [
        'auth' => [
            'confirm_token_interval' => 'PT5M',
        ]
    ],

    ValidatorInterface::class => function () {
        \Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

        return Symfony\Component\Validator\Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    },

    SymfonyValidator::class => function (ContainerInterface $container) {
        return new SymfonyValidator(
            $container->get(ValidatorInterface::class)
        );
    },

    Middleware\BodyParamsMiddleware::class => function () {
        return new Middleware\BodyParamsMiddleware();
    },

    Middleware\DomainExceptionMiddleware::class => function () {
        return new Middleware\DomainExceptionMiddleware();
    },

    Middleware\ValidationExceptionMiddleware::class => function () {
        return new Middleware\ValidationExceptionMiddleware();
    },

    Model\User\Service\Flusher::class => function (ContainerInterface $container) {
        return new ModelInfrastructure\Service\DoctrineFlusher(
            $container->get(EntityManagerInterface::class),
            $container->get(Model\EventDispatcher::class)
        );
    },

    Model\User\Entity\UserRepository::class => function (ContainerInterface $container) {
        return new ModelInfrastructure\User\Entity\DoctrineUserRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    Model\User\Service\PasswordHasher::class => function () {
        return new ModelInfrastructure\User\Service\BCryptPasswordHasher();
    },

    Model\User\Service\ConfirmTokenizer::class => function (ContainerInterface $container) {
        return new ModelInfrastructure\User\Service\RandConfirmTokenizer(
            new DateInterval($container->get('config')['auth']['confirm_token_interval'])
        );
    },
];
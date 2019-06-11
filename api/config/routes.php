<?php

use Api\Http\Action;
use Api\Http\Middleware\DomainExceptionMiddleware;
use Api\Infrastructure\Framework\Middleware\CallableMiddlewareAdapter as CM;
use Psr\Container\ContainerInterface;
use Slim\App;

return function (App $app, ContainerInterface $container) {
    $app->add(new CM($container, DomainExceptionMiddleware::class));

    $app->get('/', Action\HomeAction::class . ':handle');
    $app->post('/auth/signup', Action\Auth\SignUp\RequestAction::class . ':handle');
    $app->post('/auth/signup/confirm', Action\Auth\SignUp\ConfirmAction::class . ':handle');
};
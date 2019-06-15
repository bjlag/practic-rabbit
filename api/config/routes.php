<?php

use Api\Http\Action;
use Api\Http\Middleware;
use Api\Infrastructure\Framework\Middleware\CallableMiddlewareAdapter as CM;
use League\OAuth2\Server\Middleware\ResourceServerMiddleware;
use Psr\Container\ContainerInterface;
use Slim\App;

return function (App $app, ContainerInterface $container) {
    $app->add(new CM($container, Middleware\BodyParamsMiddleware::class));
    $app->add(new CM($container, Middleware\ValidationExceptionMiddleware::class));
    $app->add(new CM($container, Middleware\DomainExceptionMiddleware::class));

    $auth = $container->get(ResourceServerMiddleware::class);

    $app->get('/', Action\HomeAction::class . ':handle');

    $app->post('/auth/signup', Action\Auth\SignUp\RequestAction::class . ':handle');
    $app->post('/auth/signup/confirm', Action\Auth\SignUp\ConfirmAction::class . ':handle');
    $app->post('/auth/oauth', Action\Auth\OAuthAction::class . ':handle');

    $app->group('/profile', function () use ($app) {
        $app->get('', Action\Profile\ShowAction::class . ':handle');
    })->add($auth);
};
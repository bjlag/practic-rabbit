<?php

use Api\Http\Action\Auth\SignUp\RequestAction;
use Api\Http\Action\HomeAction;
use Slim\App;

return function (App $app) {
    $app->get('/', HomeAction::class . ':handle');
    $app->post('/auth/signup', RequestAction::class . ':handle');
};
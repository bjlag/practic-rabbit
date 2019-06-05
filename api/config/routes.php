<?php

use Api\Http\Action\HomeAction;
use Slim\App;

return function (App $app) {
    $app->get('/', HomeAction::class . ':handle');
};
<?php

declare(strict_types=1);

use Slim\Http\Request;
use Slim\Http\Response;

chdir(dirname(__DIR__ ));

require_once 'vendor/autoload.php';

$config = require_once 'config/config.php';

$app = new \Slim\App($config);

$app->get('/', function (Request $request, Response $response) {
    return $response->withJson([
        'name' => 'API app',
        'version' => '1.0',
    ]);
});

$app->run();
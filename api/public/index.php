<?php

declare(strict_types=1);

use Slim\Http\Request;
use Slim\Http\Response;

require_once dirname(__DIR__ ) . '/vendor/autoload.php';

$config = [
    'settings' => [
        'addContentLengthHeader' => false,
    ]
];

$app = new \Slim\App($config);

$app->get('/', function (Request $request, Response $response) {
    return $response->withJson([
        'name' => 'API app',
        'version' => '1.0',
    ]);
});

$app->run();
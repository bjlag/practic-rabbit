<?php

declare(strict_types=1);

use Api\Http\Action\HomeAction;
use Slim\App;

chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';
$config = require_once 'config/config.php';

$app = new App($config);
$app->get('/', HomeAction::class);
$app->run();
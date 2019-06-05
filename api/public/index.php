<?php

declare(strict_types=1);

use Slim\App;
use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

if (file_exists('.env')) {
    (new Dotenv())->load('.env');
}

(function () {
    $container = require_once 'config/container.php';
    $app = new App($container);

    (require_once 'config/routes.php')($app);

    $app->run();
})();

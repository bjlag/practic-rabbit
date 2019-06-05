<?php

declare(strict_types=1);

use Slim\App;

chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

(function () {
    $config = require_once 'config/config.php';
    $app = new App($config);

    (require_once 'config/routes.php')($app);

    $app->run();
})();

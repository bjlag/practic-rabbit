<?php

declare(strict_types=1);

use Slim\Container;

$config = require_once 'config/config.php';
return new Container($config);
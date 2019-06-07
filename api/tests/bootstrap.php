<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

if (file_exists('.env')) {
    (new Dotenv())->load('.env');
}
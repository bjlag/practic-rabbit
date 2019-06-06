<?php

declare(strict_types=1);

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Helper\ConfigurationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

if (file_exists('.env')) {
    (new Dotenv())->load('.env');
}

/** @var \Psr\Container\ContainerInterface $container */
$container = require_once 'config/container.php';
$cli = new Application('Console application');

/** @var EntityManagerInterface $entityManager */
$entityManager = $container->get(EntityManagerInterface::class);
$connection = $entityManager->getConnection();

$configuration = new Configuration($connection);
$configuration->setMigrationsDirectory('src/Data/Migration');
$configuration->setMigrationsNamespace('Api\Data\Migration');

$cli->getHelperSet()->set(new EntityManagerHelper($entityManager), 'em');
$cli->getHelperSet()->set(new ConfigurationHelper($connection, $configuration), 'configuration');

\Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($cli);
\Doctrine\DBAL\Migrations\Tools\Console\ConsoleRunner::addCommands($cli);

$commands = $container->get('config')['console']['commands'];
foreach ($commands as $command) {
    $cli->add($container->get($command));
}

$cli->run();

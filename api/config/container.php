<?php

declare(strict_types=1);

use Slim\Container;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;

$aggregator = new ConfigAggregator([
    new PhpFileProvider('config/common/*.php'),
]);

return new Container($aggregator->getMergedConfig());
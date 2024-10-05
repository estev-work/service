<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/global.php';

use Core\Config\Config;
use Core\DI\Container;
use Core\Logger\FileLogger;
use Project\Base\Application\Bus\CommandBusInterface;
use Project\Base\Infrastructure\Bus\CommandBus;
use Project\Modules\Questions\Api\QuestionApiInterface;
use Project\Modules\Questions\Application\Services\QuestionApplicationService;
use Psr\Log\LoggerInterface;


$container = new Container();

$loggerConfig = Config::get('logger');
$container->bind(LoggerInterface::class, function (Container $container) use ($loggerConfig) {
    return new FileLogger($loggerConfig);
});
$container->bind(CommandBusInterface::class, function (Container $container) use ($loggerConfig) {
    return new CommandBus();
});
$container->bind(QuestionApiInterface::class, function (Container $container) use ($loggerConfig) {
    return new QuestionApplicationService($container->get(CommandBusInterface::class));
});
$GLOBALS['container'] = $container;
return $container;
<?php

declare(strict_types=1);

namespace Project\Providers\Services;

use Core\Config\Config;
use Core\DI\Container;
use Core\DI\DIContainerInterface;
use Core\Logger\AppLoggerInterface;
use Core\Logger\FileLogger;
use Project\Providers\ProviderInterface;

final class LoggerProvider implements ProviderInterface
{
    public function load(DIContainerInterface $serviceContainer): void
    {
        $loggerConfig = Config::get('logger');
        $serviceContainer->bind(AppLoggerInterface::class, function (Container $container) use ($loggerConfig) {
            return new FileLogger($loggerConfig);
        });
    }
}
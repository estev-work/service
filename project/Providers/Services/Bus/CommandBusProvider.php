<?php
declare(strict_types=1);

namespace Project\Providers\Services\Bus;

use Core\DI\Container;
use Core\DI\DIContainerInterface;
use Project\Base\Application\Bus\CommandBusInterface;
use Project\Base\Infrastructure\Bus\CommandBus;
use Project\Providers\ProviderInterface;

final class CommandBusProvider implements ProviderInterface
{
    public function load(DIContainerInterface $serviceContainer): void
    {
        $serviceContainer->singleton(CommandBusInterface::class, function (Container $container) {
            return new CommandBus();
        });
    }
}
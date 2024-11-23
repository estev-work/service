<?php
declare(strict_types=1);

namespace Project\Providers\Services\Bus;

use Core\DI\Container;
use Project\Base\Application\Bus\CommandBusInterface;
use Project\Base\Infrastructure\Bus\CommandBus;
use Project\Providers\ProviderInterface;
use Psr\Container\ContainerInterface;

final class CommandBusProvider implements ProviderInterface
{
    public function load(ContainerInterface $serviceContainer): void
    {
        $serviceContainer->singleton(CommandBusInterface::class, function (Container $container) {
            return new CommandBus();
        });
    }
}
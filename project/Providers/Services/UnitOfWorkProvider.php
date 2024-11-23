<?php

declare(strict_types=1);

namespace Project\Providers\Services;

use Core\DI\Container;
use Core\DI\DIContainerInterface;
use Project\Base\Application\Bus\EventBusInterface;
use Project\Base\Application\Events\UnitOfWorkInterface;
use Project\Base\Infrastructure\Events\UnitOfWork;
use Project\Providers\ProviderInterface;

final class UnitOfWorkProvider implements ProviderInterface
{
    public function load(DIContainerInterface $serviceContainer): void
    {
        $serviceContainer->singleton(UnitOfWorkInterface::class, function (Container $container) {
            /** @var EventBusInterface $eventBus */
            $eventBus = $container->get(EventBusInterface::class);
            return new UnitOfWork($eventBus);
        });
    }
}
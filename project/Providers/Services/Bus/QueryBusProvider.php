<?php

declare(strict_types=1);

namespace Project\Providers\Services\Bus;

use Core\DI\Container;
use Core\DI\DIContainerInterface;
use Project\Base\Application\Bus\QueryBusInterface;
use Project\Base\Infrastructure\Bus\QueryBus;
use Project\Providers\ProviderInterface;

final class QueryBusProvider implements ProviderInterface
{
    public function load(DIContainerInterface $serviceContainer): void
    {
        $serviceContainer->singleton(QueryBusInterface::class, function (Container $container) {
            return new QueryBus();
        });
    }
}
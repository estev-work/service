<?php
declare(strict_types=1);

namespace Project\Providers\Services\Bus;

use Core\DI\Container;
use Project\Base\Application\Bus\QueryBusInterface;
use Project\Base\Infrastructure\Bus\QueryBus;
use Project\Providers\ProviderInterface;
use Psr\Container\ContainerInterface;

final class QueryBusProvider implements ProviderInterface
{
    public function load(ContainerInterface $serviceContainer): void
    {
        $serviceContainer->singleton(QueryBusInterface::class, function (Container $container) {
            return new QueryBus();
        });
    }
}
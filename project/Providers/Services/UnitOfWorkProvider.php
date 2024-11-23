<?php
declare(strict_types=1);

namespace Project\Providers\Services;

use Core\DI\Container;
use Project\Base\Application\Bus\EventBusInterface;
use Project\Base\Application\Events\UnitOfWorkInterface;
use Project\Base\Infrastructure\Events\UnitOfWork;
use Project\Providers\ProviderInterface;
use Psr\Container\ContainerInterface;

final class UnitOfWorkProvider implements ProviderInterface
{
    public function load(ContainerInterface $serviceContainer): void
    {

        $serviceContainer->singleton(UnitOfWorkInterface::class, function (Container $container) {
            /** @var EventBusInterface $eventBus */
            $eventBus = $container->get(EventBusInterface::class);
            return new UnitOfWork($eventBus);
        });
    }
}
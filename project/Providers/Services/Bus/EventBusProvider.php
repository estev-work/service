<?php

declare(strict_types=1);

namespace Project\Providers\Services\Bus;

use Core\DI\Container;
use Core\DI\DIContainerInterface;
use Exception;
use Project\Base\Application\Bus\EventBusInterface;
use Project\Base\Application\Bus\MessageBrokerInterface;
use Project\Base\Infrastructure\Bus\EventBus;
use Project\Providers\ProviderInterface;

final class EventBusProvider implements ProviderInterface
{
    /**
     * @throws Exception
     */
    public function load(DIContainerInterface $serviceContainer): void
    {
        $serviceContainer->singleton(EventBusInterface::class, function (Container $container) {
            return new EventBus($container->get(MessageBrokerInterface::class));
        });
    }
}
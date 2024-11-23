<?php
declare(strict_types=1);

namespace Project\Providers\Services\Bus;

use Core\DI\Container;
use Exception;
use Project\Base\Application\Bus\EventBusInterface;
use Project\Base\Application\Bus\MessageBrokerInterface;
use Project\Base\Infrastructure\Bus\EventBus;
use Project\Providers\ProviderInterface;
use Psr\Container\ContainerInterface;

final class EventBusProvider implements ProviderInterface
{
    /**
     * @throws Exception
     */
    public function load(ContainerInterface $serviceContainer): void
    {
        $serviceContainer->singleton(EventBusInterface::class, function (Container $container) {
            return new EventBus($container->get(MessageBrokerInterface::class));
        });
    }
}
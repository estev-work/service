<?php

declare(strict_types=1);

namespace Project\Providers\Services\Modules;

use Core\DI\Container;
use Core\DI\DIContainerInterface;
use Project\Base\Application\Bus\CommandBusInterface;
use Project\Modules\Activities\Api\ActivityApiInterface;
use Project\Modules\Activities\Application\Services\ActivityApplicationService;
use Project\Modules\Notifications\Api\NotificationApiInterface;
use Project\Modules\Notifications\Application\Services\NotificationApplicationService;
use Project\Providers\ProviderInterface;

final class ApiProvider implements ProviderInterface
{
    public function load(DIContainerInterface $serviceContainer): void
    {
        $serviceContainer->bind(ActivityApiInterface::class, function (Container $container) {
            return new ActivityApplicationService($container->get(CommandBusInterface::class));
        });
        $serviceContainer->bind(NotificationApiInterface::class, function (Container $container) {
            return new NotificationApplicationService($container->get(CommandBusInterface::class));
        });
    }
}
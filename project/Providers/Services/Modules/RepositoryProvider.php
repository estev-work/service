<?php
declare(strict_types=1);

namespace Project\Providers\Services\Modules;

use Core\DI\Container;
use Core\Logger\AppLoggerInterface;
use PDO;
use Project\Modules\Activities\Domain\Repositories\ActivityRepositoryInterface;
use Project\Modules\Activities\Infrastructure\Repositories\ActivityRepository;
use Project\Providers\ProviderInterface;
use Psr\Container\ContainerInterface;

final class RepositoryProvider implements ProviderInterface
{
    public function load(ContainerInterface $serviceContainer): void
    {
        $serviceContainer->bind(ActivityRepositoryInterface::class, function (Container $container) {
            return new ActivityRepository($container->get(PDO::class), $container->get(AppLoggerInterface::class));
        });
    }
}
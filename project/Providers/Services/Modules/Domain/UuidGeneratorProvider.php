<?php

namespace Project\Providers\Services\Modules\Domain;

use Core\DI\Container;
use Core\DI\DIContainerInterface;
use Project\Base\Domain\Common\Uuid\UuidFactoryInterface;
use Project\Base\Infrastructure\Common\Uuid\BaseUuidFactory;
use Project\Providers\ProviderInterface;

final class UuidGeneratorProvider implements ProviderInterface
{
    public function load(DIContainerInterface $serviceContainer): void
    {
        $serviceContainer->bind(UuidFactoryInterface::class, function (Container $container) {
            return new BaseUuidFactory();
        });
    }
}
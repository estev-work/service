<?php

declare(strict_types=1);

namespace Project\Providers;

use Core\DI\DIContainerInterface;
use Exception;

final readonly class RegistryService
{
    public function __construct(private DIContainerInterface $container)
    {
    }

    /**
     * @throws Exception
     */
    public function registry(ProviderInterface ...$providers): void
    {
        foreach ($providers as $provider) {
            $provider->load($this->container);
        }
    }
}
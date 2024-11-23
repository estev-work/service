<?php
declare(strict_types=1);

namespace Project\Providers;

use Exception;
use Psr\Container\ContainerInterface;

final readonly class RegistryService
{
    public function __construct(private ContainerInterface $container)
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
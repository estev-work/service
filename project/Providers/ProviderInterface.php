<?php

namespace Project\Providers;

use Exception;
use Psr\Container\ContainerInterface;

interface ProviderInterface
{
    /**
     * @throws Exception
     */
    public function load(ContainerInterface $serviceContainer): void;
}
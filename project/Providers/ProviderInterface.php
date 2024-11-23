<?php

namespace Project\Providers;

use Core\DI\DIContainerInterface;
use Exception;

interface ProviderInterface
{
    /**
     * @throws Exception
     */
    public function load(DIContainerInterface $serviceContainer): void;
}
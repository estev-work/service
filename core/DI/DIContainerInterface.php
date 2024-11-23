<?php

namespace Core\DI;

use Psr\Container\ContainerInterface;

interface DIContainerInterface extends ContainerInterface
{
    public function bind(string $abstract, \Closure $concrete): void;
    public function singleton(string $abstract, \Closure $concrete): void;
}
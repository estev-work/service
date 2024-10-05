<?php

declare(strict_types=1);


namespace Core\DI;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;

final class Container implements ContainerInterface
{
    private array $services = [];

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function get(string $id): object
    {
        if ($this->has($id)) {
            return $this->services[$id]($this);
        }
        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]) || class_exists($id);
    }

    public function set(string $id, callable $resolver, bool $singleton = false): void
    {
        if ($singleton) {
            $this->services[$id] = function ($c) use ($resolver) {
                static $instance;
                if ($instance === null) {
                    $instance = $resolver($c);
                }
                return $instance;
            };
        } else {
            $this->services[$id] = $resolver;
        }
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    private function resolve(string $id): object
    {
        if (!class_exists($id)) {
            throw new Exception("Class {$id} does not exist.");
        }

        $reflectionClass = new ReflectionClass($id);

        if (!$constructor = $reflectionClass->getConstructor()) {
            return new $id();
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();

            if ($dependency === null) {
                throw new Exception("Cannot resolve class dependency for {$parameter->name}");
            }

            $dependencies[] = $this->get($dependency->name);
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}

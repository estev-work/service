<?php

declare(strict_types=1);

namespace Core\DI;

use Exception;
use ReflectionClass;
use ReflectionException;

final class Container implements DIContainerInterface
{
    private array $instances = [];
    private array $bindings = [];
    private array $singletons = [];

    public function bind(string $abstract, \Closure $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton(string $abstract, \Closure $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
        $this->singletons[$abstract] = true;
    }

    /**
     * @throws Exception
     */
    public function get(string $id)
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (isset($this->bindings[$id])) {
            $concrete = $this->bindings[$id];

            if (is_callable($concrete)) {
                $object = $concrete($this);

                if (isset($this->singletons[$id])) {
                    $this->instances[$id] = $object;
                }

                return $object;
            }

            $object = $this->resolve($concrete);

            if (isset($this->singletons[$id])) {
                $this->instances[$id] = $object;
            }

            return $object;
        }

        $object = $this->resolve($id);

        if (isset($this->singletons[$id])) {
            $this->instances[$id] = $object;
        }

        return $object;
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || class_exists($id);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    private function resolve(string $class)
    {
        $reflector = new ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new Exception("Класс {$class} не может быть создан.");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $class;
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->resolveDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * @throws Exception
     */
    private function resolveDependencies(array $parameters): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type && !$type->isBuiltin()) {
                $dependency = $type->getName();

                $dependencies[] = $this->get($dependency);
            } else {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Не удалось разрешить зависимость для параметра {$parameter->getName()}");
                }
            }
        }

        return $dependencies;
    }
}
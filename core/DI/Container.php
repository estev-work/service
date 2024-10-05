<?php

declare(strict_types=1);


namespace Core\DI;

use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

final class Container implements ContainerInterface
{
    private array $instances = [];
    private array $bindings = [];

    public function bind(string $abstract, $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
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
                return $this->instances[$id] = $concrete($this);
            }

            return $this->instances[$id] = $this->resolve($concrete);
        }

        return $this->resolve($id);
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
            return $this->instances[$class] = new $class;
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->resolveDependencies($parameters);

        return $this->instances[$class] = $reflector->newInstanceArgs($dependencies);
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
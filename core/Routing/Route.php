<?php

declare(strict_types=1);

namespace Core\Routing;

use Core\Http\Request;
use Exception;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;
use ReflectionMethod;

final class Route
{
    private string $method;
    private string $path;
    private string $controller;
    private string $action;
    private string $name;
    private array $params = [];

    public function __construct(string $method, string $path, string $controller, string $action, string $name)
    {
        $this->method = $method;
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
        $this->name = $name;

        $this->params = $this->extractParameters($path);
    }

    /**
     * @throws Exception
     */
    public function execAction(Request $request): ResponseInterface|string
    {
        $controllerClass = $this->getControllerClassName();
        $action = $this->getAction();
        $controller = resolve($controllerClass);
        $args = [];
        try {
            $reflectionMethod = new ReflectionMethod($controller, $action);

            $parameters = $reflectionMethod->getParameters();

            foreach ($parameters as $index => $parameter) {
                if (in_array($parameter->getName(), $this->params)) {
                    $args[$parameter->getName()] = $this->getParametersValue($request->getUri()->getPath())[$parameter->getName()];
                }
            }
        } catch (ReflectionException $e) {
            throw new Exception('Parameter injection failed: ' . $e->getMessage());
        }
        return $controller->{$action}($request, ...$args);
    }

    private function getParametersValue(string $uri): array
    {
        $pattern = preg_replace('/:(\w+)/', '(?P<$1>[a-zA-Z0-9_-]+)', $this->getPath());

        $pattern = "#^" . $pattern . "$#";

        if (preg_match($pattern, $uri, $matches)) {
            return array_filter(
                $matches,
                function ($key) {
                    return !is_int($key);
                },
                ARRAY_FILTER_USE_KEY
            );
        }
        return [];
    }

    private function extractParameters(string $path): array
    {
        preg_match_all('/:(\w+)/', $path, $matches);
        return $matches[1];
    }

    private function getPath(): string
    {
        return $this->path;
    }

    private function getControllerClassName(): string
    {
        return '\\' . str_replace("/", "\\", $this->controller);
    }

    private function getAction(): string
    {
        return $this->action;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getName(): string
    {
        return $this->name;
    }

}
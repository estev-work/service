<?php

declare(strict_types=1);

namespace Core\Routing;

use Nyholm\Psr7\Uri;
use Symfony\Component\Yaml\Yaml;

final class Router
{
    private array $routes = [];
    private array $sections = [];

    public function __construct(array $sections)
    {
        $this->sections = $sections;
        $routePath = __DIR__ . '/../../route/route.yaml';
        $parsedYaml = Yaml::parseFile($routePath);
        foreach ($this->sections as $section => $prefix) {
            $this->routes[$section] = $this->parseRoutes($prefix, $parsedYaml[$section]);
        }
    }

    public function findRoute(string $method, Uri $uri): Route|null
    {
        foreach ($this->sections as $section => $prefix) {
            if ($this->isSection($uri->getPath(), $prefix)) {
                if (in_array($method, array_keys($this->routes[$section]))) {
                    foreach ($this->routes[$section][$method] as $path => $route) {
                        if ($this->matchRoute($uri->getPath(), $path)) {
                            return $route;
                        }
                    }
                }
            }
        }
        return null;
    }

    private function parseRoutes(string $prefix, array $section): array
    {
        $routeList = [];
        try {
            foreach ($section as $routes) {
                foreach ($routes as $name => $route) {
                    if (!isset($route['path'])) {
                        throw new \InvalidArgumentException("Маршрут '{$name}' должен содержать 'path'");
                    }

                    if (!isset($route['methods'])) {
                        throw new \InvalidArgumentException("Маршрут '{$name}' должен содержать 'methods'");
                    }

                    if (!isset($route['controller'])) {
                        throw new \InvalidArgumentException("Маршрут '{$name}' должен содержать 'controller'");
                    }

                    if (!isset($route['action'])) {
                        throw new \InvalidArgumentException("Маршрут '{$name}' должен содержать 'action'");
                    }

                    $path = $prefix . $route['path'];
                    $methods = $route['methods'];
                    $controller = $route['controller'];
                    $action = $route['action'];
                    if (!is_array($methods)) {
                        $methods = [$methods];
                    }
                    foreach ($methods as $method) {
                        $routeList[$method][$path] = $this->makeRoute(
                            method: $method,
                            path: $path,
                            controller: $controller,
                            action: $action,
                            name: $name
                        );
                    }
                }
            }
            return $routeList;
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException('Ошибка в маршрутах: ' . $e->getMessage());
        } catch (\Throwable $e) {
            throw new \Exception('Ошибка при парсинге маршрутов: ' . $e->getMessage());
        }
    }

    private function makeRoute(string $method, string $path, string $controller, string $action, string $name): Route
    {
        $method = strtoupper(trim($method));
        if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD'])) {
            throw new \InvalidArgumentException("Недопустимый HTTP-метод '{$method}' для маршрута '{$name}'");
        }

        return new Route($method, $path, $controller, $action, $name);
    }

    private function isSection(string $uri, string $prefix): bool
    {
        return str_starts_with($uri, $prefix);
    }

    private function matchRoute(string $uri, string $path): bool
    {
        $pattern = preg_replace('/:\w+/', '([a-zA-Z0-9_-]+)', $path);

        $pattern = "#^" . $pattern . "$#";

        return (bool)preg_match($pattern, $uri);
    }
}
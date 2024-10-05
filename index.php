<?php

$container = require __DIR__ . '/core/bootstrap.php';

use Core\Http\Request;
use Core\Routing\Route;
use Core\Routing\Router;

try {
    $router = new Router([
        'api' => '/api/v1/',
    ]);

    $request = new Request();

    /** @var Route $route */
    $route = $router->findRoute($request->getMethod(), $request->getUri());

    if (!$route) {
        throw new HttpException('route not found', 404);
    }
    echo $route->execAction($request)->getBody();
} catch (Throwable $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'error' => $e->getMessage(),
        'code' => $e->getCode(),
        'trace' => $e->getTrace(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
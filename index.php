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
    echo json_encode($e->getMessage());
}
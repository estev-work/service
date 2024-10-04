<?php

$container = require __DIR__ . '/app/bootstrap.php';

use App\Core\Http\Request;
use App\Core\Route;
use App\Core\Router;

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

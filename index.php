<?php
require __DIR__ . '/core/bootstrap.php';
require __DIR__ . '/core/global.php';
$container = bootstrap();

use Core\Http\Request;
use Core\Routing\Route;
use Core\Routing\Router;
use Psr\Log\LoggerInterface;

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
    $message = json_encode([
        'error' => $e->getMessage(),
        'code' => $e->getCode(),
        'trace' => $e->getTrace(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    try {
        $logger = resolve(LoggerInterface::class);
        $logger->error($message);
    } catch (Exception $e) {
    }
    header('Content-Type: application/json');
    echo $message;
}
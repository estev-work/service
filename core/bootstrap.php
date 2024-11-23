<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Core\DI\Container;
use Core\Logger\AppLoggerInterface;

/**
 * @throws Exception
 */
function bootstrap(): \Core\DI\DIContainerInterface
{
    try {
        $container = new Container();
        $registry = new \Project\Providers\RegistryService($container);
        $registry->registry(
            new \Project\Providers\Services\LoggerProvider(),
            new \Project\Providers\Services\PDOProvider(),
            new \Project\Providers\Services\Bus\CommandBusProvider(),
            new \Project\Providers\Services\Bus\QueryBusProvider(),
            new \Project\Providers\Services\MessageBrokerProvider(),
            new \Project\Providers\Services\Bus\EventBusProvider(),
            new \Project\Providers\Services\UnitOfWorkProvider(),
            new \Project\Providers\Services\Modules\ApiProvider(),
            new \Project\Providers\Services\Modules\RepositoryProvider(),
        );

        $GLOBALS['container'] = $container;
        return $container;
    } catch (\Throwable $exception) {
        $message = json_encode([
            'error' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'trace' => $exception->getTrace(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        try {
            $logger = resolve(AppLoggerInterface::class);
            $logger->error($message);
        } catch (Exception $e) {
        }
        header('Content-Type: application/json');
        echo $message;
    }
    throw new Exception('Internal Error');
}
<?php
declare(strict_types=1);

namespace Project\Providers\Services;

use Core\Logger\AppLoggerInterface;
use Core\Logger\FileLogger;
use Project\Base\Application\Events\MessageHandlerInterface;
use Project\Base\Infrastructure\Messaging\MessageHandler;
use Project\Providers\ProviderInterface;
use Psr\Container\ContainerInterface;

final class MessageHandlerProvider implements ProviderInterface
{
    public function load(ContainerInterface $serviceContainer): void
    {
        $serviceContainer->singleton(MessageHandlerInterface::class, function () {
            /** @var FileLogger $logger */
            $logger = resolve(AppLoggerInterface::class);
            $logger->setChannel('message-handler');
            return new MessageHandler($logger);
        });
    }
}
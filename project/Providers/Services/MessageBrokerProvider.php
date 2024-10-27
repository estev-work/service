<?php
declare(strict_types=1);

namespace Project\Providers\Services;

use Core\Config\Config;
use Core\DI\Container;
use Core\Logger\AppLoggerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Project\Base\Application\Bus\MessageBrokerInterface;
use Project\Base\Infrastructure\Messaging\RabbitMQ\RabbitMQMessageBroker;
use Project\Providers\ProviderInterface;
use Psr\Container\ContainerInterface;

final class MessageBrokerProvider implements ProviderInterface
{
    public function load(ContainerInterface $serviceContainer): void
    {

        $serviceContainer->bind(MessageBrokerInterface::class, function (Container $container) {
            /** @var AppLoggerInterface $logger */
            $logger = $container->get(AppLoggerInterface::class);
            $logger->setChannel('rabbitmq-work');
            $servicesConfig = Config::get('services.broker.rabbitmq');
            $connection = new AMQPStreamConnection(
                $servicesConfig['host'],
                (int)$servicesConfig['port'],
                $servicesConfig['user'],
                $servicesConfig['password']
            );
            return new RabbitMQMessageBroker($connection, $logger);
        });
    }
}
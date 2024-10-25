<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/global.php';

use Core\Config\Config;
use Core\DI\Container;
use Core\Logger\AppLoggerInterface;
use Core\Logger\FileLogger;
use Project\Base\Application\Bus\CommandBusInterface;
use Project\Base\Application\Bus\EventBusInterface;
use Project\Base\Application\Bus\MessageBrokerInterface;
use Project\Base\Application\Bus\QueryBusInterface;
use Project\Base\Application\Events\MessageHandlerInterface;
use Project\Base\Application\Events\UnitOfWorkInterface;
use Project\Base\Infrastructure\Bus\CommandBus;
use Project\Base\Infrastructure\Bus\EventBus;
use Project\Base\Infrastructure\Bus\QueryBus;
use Project\Base\Infrastructure\Events\UnitOfWork;
use Project\Base\Infrastructure\Messaging\Kafka\KafkaMessageBroker;
use Project\Base\Infrastructure\Messaging\MessageHandler;
use Project\Modules\Activities\Api\ActivityApiInterface;
use Project\Modules\Activities\Application\Services\ActivityApplicationService;
use Project\Modules\Activities\Domain\Repositories\ActivityRepositoryInterface;
use Project\Modules\Activities\Infrastructure\Repositories\ActivityRepository;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\Message;
use RdKafka\Producer;


$container = new Container();

$loggerConfig = Config::get('logger');
$container->bind(AppLoggerInterface::class, function (Container $container) use ($loggerConfig) {
    return new FileLogger($loggerConfig);
});
$container->singleton(CommandBusInterface::class, function (Container $container) {
    return new CommandBus();
});
$container->singleton(QueryBusInterface::class, function (Container $container) {
    return new QueryBus();
});
$container->singleton(MessageHandlerInterface::class, function (Container $container) {
    /** @var FileLogger $logger */
    $logger = resolve(AppLoggerInterface::class);
    $logger->setChannel('kafka-work');
    return new MessageHandler($logger);
});

$servicesConfig = Config::get('services');
$container->bind(MessageBrokerInterface::class, function (Container $container) use ($servicesConfig) {
    /** @var AppLoggerInterface $logger */
    $logger = resolve(AppLoggerInterface::class);
    $logger->setChannel('kafka-work');
    $producerConf = new Conf();
    $producerConf->set(
        'metadata.broker.list',
        $servicesConfig['broker']['kafka']['brokers']
    );
    $producerConf->setDrMsgCb(function (Producer $producer, Message $message) use ($logger) {
        if ($message->err) {
            $logger->error(
                "Message delivery failed: " . rd_kafka_err2str($message->err)
            );
        } else {
            $logger->info(
                "Message delivered to partition " . $message->partition . " at offset " . $message->offset
            );
        }
    });
    $producer = new Producer($producerConf);

    $consumerConf = new Conf();
    $consumerConf->set('metadata.broker.list', $servicesConfig['broker']['kafka']['brokers']);
    $consumerConf->set('client.id', $servicesConfig['broker']['kafka']['client_id']);
    $consumerConf->set('group.id', $servicesConfig['broker']['kafka']['group_id']);
    $consumerConf->set('auto.offset.reset', 'earliest');
    $consumerConf->set('enable.auto.commit', 0);
    $consumer = new KafkaConsumer($consumerConf);

    return new KafkaMessageBroker($producer, $consumer);
});
$container->singleton(EventBusInterface::class, function (Container $container) {
    /** @var MessageBrokerInterface $broker */
    $broker = resolve(MessageBrokerInterface::class);
    return new EventBus($broker);
});
$container->singleton(UnitOfWorkInterface::class, function (Container $container) {
    /** @var EventBusInterface $eventBus */
    $eventBus = resolve(EventBusInterface::class);
    return new UnitOfWork($eventBus);
});
$container->bind(ActivityApiInterface::class, function (Container $container) {
    return new ActivityApplicationService($container->get(CommandBusInterface::class));
});

$container->singleton(PDO::class, function ($app) {
    $dsn = "mysql:host=" . Config::get('database.connections.mysql.host')
        . ";dbname=" . Config::get('database.connections.mysql.database')
        . ";port=" . Config::get('database.connections.mysql.port');
    $username = Config::get('database.connections.mysql.username');
    $password = Config::get('database.connections.mysql.password');

    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
});

$container->bind(ActivityRepositoryInterface::class, function (Container $container) {
    return new ActivityRepository($container->get(PDO::class), $container->get(AppLoggerInterface::class));
});

$GLOBALS['container'] = $container;
return $container;
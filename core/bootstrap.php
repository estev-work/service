<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/global.php';

use Core\Config\Config;
use Core\DI\Container;
use Core\Logger\FileLogger;
use Project\Base\Application\Bus\CommandBusInterface;
use Project\Base\Application\Bus\EventBusInterface;
use Project\Base\Application\Bus\QueryBusInterface;
use Project\Base\Infrastructure\Bus\CommandBus;
use Project\Base\Infrastructure\Bus\EventBus;
use Project\Base\Infrastructure\Bus\QueryBus;
use Project\Base\Infrastructure\Messaging\Kafka\KafkaMessageBroker;
use Project\Base\Infrastructure\Messaging\MessageBrokerInterface;
use Project\Modules\Questions\Api\QuestionApiInterface;
use Project\Modules\Questions\Application\Services\QuestionApplicationService;
use Project\Modules\Questions\Domain\Repositories\QuestionRepositoryInterface;
use Project\Modules\Questions\Infrastructure\Repositories\QuestionRepository;
use Psr\Log\LoggerInterface;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\Message;
use RdKafka\Producer;


$container = new Container();

$loggerConfig = Config::get('logger');
$container->bind(LoggerInterface::class, function (Container $container) use ($loggerConfig) {
    return new FileLogger($loggerConfig);
});
$container->singleton(CommandBusInterface::class, function (Container $container) {
    return new CommandBus();
});
$container->singleton(QueryBusInterface::class, function (Container $container) {
    return new QueryBus();
});

$servicesConfig = Config::get('services');
$container->bind(MessageBrokerInterface::class, function (Container $container) use ($servicesConfig) {
    /** @var LoggerInterface $logger */
    $logger = $container->get(LoggerInterface::class);
    $logger->$producerConf = new Conf();
    $producerConf->set(
        'metadata.broker.list',
        $servicesConfig['broker']['kafka']['brokers']
    );
    $producerConf->setDrMsgCb(function (Producer $producer, Message $message) {
        if ($message->err) {
            Log::channel('kafka')->error(
                "Message delivery failed: " . rd_kafka_err2str($message->err)
            );
        } else {
            Log::channel('kafka')->info(
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
    return new EventBus(resolve(MessageBrokerInterface::class));
});
$container->bind(QuestionApiInterface::class, function (Container $container) {
    return new QuestionApplicationService($container->get(CommandBusInterface::class));
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

$container->bind(QuestionRepositoryInterface::class, function (Container $container) {
    return new QuestionRepository($container->get(PDO::class), $container->get(LoggerInterface::class));
});

$GLOBALS['container'] = $container;
return $container;
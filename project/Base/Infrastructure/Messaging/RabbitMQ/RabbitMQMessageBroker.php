<?php

namespace Project\Base\Infrastructure\Messaging\RabbitMQ;

use Core\Logger\AppLoggerInterface;
use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Project\Base\Application\Bus\MessageBrokerInterface;
use Project\Base\Domain\Events\EventInterface;

readonly class RabbitMQMessageBroker implements MessageBrokerInterface
{
    public function __construct(private AMQPStreamConnection $connection, private AppLoggerInterface $logger)
    {
    }

    public function publish(EventInterface $event): void
    {
        $channel = $this->connection->channel();
        $exchange = explode('.', $event->getName())[0];
        $headers = new AMQPTable([
            'x-uniq-key' => $event->getKey()
        ]);
        $msg = new AMQPMessage(
            json_encode($event->getPayload()),
            ['application_headers' =>$headers]
        );
        $channel->basic_publish($msg, $exchange . '_exchange', $event->getName());
        $channel->close();
    }

    public function subscribe(string $queueName, callable $callback): void
    {
        $this->logger->info("Subscribing event {$queueName}");
        $channel = $this->connection->channel();
        $channel->basic_consume(
            queue: $queueName,
            callback: $callback
        );
        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        $this->connection->close();
    }
}

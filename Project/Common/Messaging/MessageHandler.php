<?php

namespace Project\Common\Messaging;

use Exception;
use Illuminate\Console\Command;
use Project\Base\Application\Bus\EventBusInterface;
use Project\Base\Domain\Events\EventInterface;
use RdKafka\Message;
use ReflectionClass;
use ReflectionException;

readonly class MessageHandler
{
    private EventBusInterface $eventBus;

    public function __construct(protected Command $command)
    {
        $this->eventBus = app(EventBusInterface::class);
    }

    /**
     * @throws Exception
     */
    public function process(Message $message): bool
    {
        $payload = json_decode($message->payload, true)['payload'];
        $this->command->info("Processed event: {$message->topic_name}");
        return $this->eventBus->handle($message->topic_name, $message->key, $payload, $message->headers);
    }

    /**
     * Создает объект события на основе переданных данных.
     *
     * @param string $eventClass
     * @param array  $eventData
     *
     * @return EventInterface|null
     */
    private function createEventFromData(string $eventClass, array $eventData): ?EventInterface
    {
        try {
            $reflection = new ReflectionClass($eventClass);
            return $reflection->newInstanceArgs($eventData);
        } catch (ReflectionException $e) {
            $this->command->error("Error creating event: " . $e->getMessage());
            return null;
        }
    }
}

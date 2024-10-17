<?php

declare(strict_types=1);

namespace Project\Base\Infrastructure\Messaging;

use Project\Base\Application\Events\EventDataInterface;

final readonly class EventData implements EventDataInterface
{
    private string $key;
    private string $eventName;
    private array $payload;

    public function __construct(string $key, array $message)
    {
        $this->key = $key;
        $this->eventName = $message['eventName'];
        $this->payload = $message['payload'];
    }

    public function getName(): string
    {
        return $this->eventName;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }
}
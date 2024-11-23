<?php

declare(strict_types=1);

namespace Project\Base\Infrastructure\Messaging;

use Project\Base\Application\Events\MessageInterface;

final readonly class Message implements MessageInterface
{
    private string $key;
    private string $eventName;
    private array $payload;

    public function __construct(string $key, string $eventName, array $payload)
    {
        $this->key = $key;
        $this->eventName = $eventName;
        $this->payload = $payload;
    }

    public function getName(): string
    {
        return $this->eventName;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
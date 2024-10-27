<?php

namespace Project\Base\Application\Events;

interface MessageInterface
{
    public function __construct(string $key, string $eventName, array $payload);

    public function getName(): string;

    public function getKey(): string;

    public function getPayload(): array;
}
<?php

namespace Project\Base\Application\Events;

interface EventDataInterface
{
    public function __construct(string $key, array $message);

    public function getName(): string;

    public function getKey(): string;

    public function getPayload(): string;
}
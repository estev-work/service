<?php

namespace Project\Base\Domain\Events;

interface EventInterface
{
    public function getName(): string;

    public function getKey(): string;

    public function getPayload(): array;
}

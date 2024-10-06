<?php

declare(strict_types=1);

namespace Project\Common\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD)]
final class EventHandler
{
    public string $eventName;

    public function __construct(string $eventName)
    {
        $this->eventName = $eventName;
    }
}
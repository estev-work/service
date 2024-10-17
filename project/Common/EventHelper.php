<?php

declare(strict_types=1);

namespace Project\Common;

class EventHelper
{
    public static function getTopicName(string $className, bool $reply = false): string|null
    {
        try {
            $eventClassName = basename(str_replace('\\', '/', $className));
            $eventClassName = preg_replace('/Event$/', '', $eventClassName);
            $words = preg_split('/(?=[A-Z])/', $eventClassName);
            $words = array_filter($words);
            $eventName = strtolower(implode('.', $words));
            if ($reply) {
                return $eventName . '.reply';
            }
            return $eventName;
        } catch (\Throwable $exception) {
            return null;
        }
    }
}

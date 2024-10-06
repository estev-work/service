<?php

declare(strict_types=1);

namespace Project\Common\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class EventListener
{
    public function __construct()
    {
    }
}

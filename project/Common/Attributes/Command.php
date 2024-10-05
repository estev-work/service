<?php

declare(strict_types=1);

namespace Project\Common\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Command
{
    public string $command;

    public function __construct(string $command)
    {
        $this->command = $command;
    }
}

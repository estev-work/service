<?php

declare(strict_types=1);

namespace Project\Base\Application\Commands;

abstract class AbstractCommandHandler
{
    protected function assertCorrectCommand(CommandInterface $command, string $expectedClass): void
    {
        if (!$command instanceof $expectedClass) {
            throw new \InvalidArgumentException("Invalid command type. Expected: $expectedClass");
        }
    }
}

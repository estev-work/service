<?php

namespace Project\Base\Application\Bus;

use Project\Base\Application\Commands\CommandHandlerInterface;
use Project\Base\Application\Commands\CommandInterface;

interface CommandBusInterface
{
    public function register(CommandHandlerInterface $handler): void;

    public function handle(CommandInterface $command): mixed;
}

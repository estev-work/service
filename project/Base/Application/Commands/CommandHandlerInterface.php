<?php

namespace Project\Base\Application\Commands;

interface CommandHandlerInterface
{
    public function execute(CommandInterface $command): mixed;
}

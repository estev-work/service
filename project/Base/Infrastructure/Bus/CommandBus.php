<?php

namespace Project\Base\Infrastructure\Bus;

use Exception;
use Project\Base\Application\Bus\CommandBusInterface;
use Project\Base\Application\Commands\CommandHandlerInterface;
use Project\Base\Application\Commands\CommandInterface;
use Project\Common\Attributes\CommandHandler;

class CommandBus implements CommandBusInterface
{
    private array $handlers = [];

    /**
     * @throws Exception
     */
    public function register(CommandHandlerInterface $handler): void
    {
        $this->handlers[$this->getCommandForHandler($handler)] = $handler;
    }

    public function handle(CommandInterface $command): mixed
    {
        $commandClass = get_class($command);

        if (!isset($this->handlers[$commandClass])) {
            throw new \Exception("Handler for command {$commandClass} not found.");
        }
        /** @var CommandHandlerInterface $handler */
        $handler = $this->handlers[$commandClass];
        return $handler->execute($command);
    }

    /**
     * @throws Exception
     */
    private function getCommandForHandler(CommandHandlerInterface $handler): string
    {
        $reflectionClass = new \ReflectionClass($handler);
        $attributes = $reflectionClass->getAttributes(CommandHandler::class);
        if ($attributes) {
            return $attributes[0]->getName();
        }
        throw new Exception("Handler not has command.");
    }
}

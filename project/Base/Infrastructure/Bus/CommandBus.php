<?php

namespace Project\Base\Infrastructure\Bus;

use Exception;
use Project\Base\Application\Bus\CommandBusInterface;
use Project\Base\Application\Commands\CommandHandlerInterface;
use Project\Base\Application\Commands\CommandInterface;
use Project\Common\Attributes\Command;
use ReflectionClass;

class CommandBus implements CommandBusInterface
{
    private array $handlers
        = [];

    /**
     * @throws Exception
     */
    public function register(CommandHandlerInterface $handler): void
    {
        $this->handlers[$this->getCommandForHandler($handler)] = $handler;
    }

    /**
     * @throws Exception
     */
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
        $reflectionClass = new ReflectionClass($handler::class);

        $attributes = $reflectionClass->getAttributes(Command::class);

        if (!empty($attributes)) {
            try {
                $commandAttribute = $attributes[0]->getArguments();
                return $commandAttribute["command"];
            } catch (Exception $exception) {
                throw new Exception("Command handler for command {$commandClass} not found.");
            }
        }
        throw new Exception("Handler not has command.");
    }
}

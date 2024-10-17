<?php

namespace Project\Base\Infrastructure\Bus;

use Exception;
use Project\Base\Application\Bus\QueryBusInterface;
use Project\Base\Application\Queries\QueryHandlerInterface;
use Project\Base\Application\Queries\QueryInterface;
use Project\Common\Attributes\QueryHandler;

class QueryBus implements QueryBusInterface
{
    private array $handlers = [];

    /**
     * @throws Exception
     */
    public function register(QueryHandlerInterface $handler): void
    {
        $this->handlers[$this->getQueryForHandler($handler)] = $handler;
    }

    public function handle(QueryInterface $query): mixed
    {
        $queryClass = get_class($query);

        if (!isset($this->handlers[$queryClass])) {
            throw new \Exception("Handler for query {$queryClass} not found.");
        }
        /** @var QueryHandlerInterface $handler */
        $handler = $this->handlers[$queryClass];
        return $handler->execute($query);
    }

    /**
     * @throws Exception
     */
    private function getQueryForHandler(QueryHandlerInterface $handler): string
    {
        $reflectionClass = new \ReflectionClass($handler);
        $attributes = $reflectionClass->getAttributes(QueryHandler::class);
        if ($attributes) {
            return $attributes[0]->getName();
        }
        throw new Exception("Handler not has command.");
    }
}

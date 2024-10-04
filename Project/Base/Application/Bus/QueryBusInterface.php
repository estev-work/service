<?php

namespace Project\Base\Application\Bus;

use Project\Base\Application\Queries\QueryHandlerInterface;
use Project\Base\Application\Queries\QueryInterface;

interface QueryBusInterface
{
    public function register(QueryHandlerInterface $handler): void;

    public function handle(QueryInterface $query): mixed;
}

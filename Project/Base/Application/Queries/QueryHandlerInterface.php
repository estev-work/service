<?php

namespace Project\Base\Application\Queries;

interface QueryHandlerInterface
{
    public function execute(QueryInterface $query): mixed;
}

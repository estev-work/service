<?php

declare(strict_types=1);

namespace Project\Common\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class QueryHandler
{
    public string $query;

    public function __construct(string $query)
    {
        $this->query = $query;
    }
}

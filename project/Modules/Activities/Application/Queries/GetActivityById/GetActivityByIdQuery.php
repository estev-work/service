<?php

namespace Project\Modules\Activities\Application\Queries\GetActivityById;

use Project\Base\Application\Queries\QueryInterface;

readonly class GetActivityByIdQuery implements QueryInterface
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}

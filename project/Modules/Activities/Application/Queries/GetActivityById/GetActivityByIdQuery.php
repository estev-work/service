<?php

namespace Project\Modules\Activities\Application\Queries\GetActivityById;

use Project\Base\Application\Queries\QueryInterface;

class GetActivityByIdQuery implements QueryInterface
{
    private(set) string $id {
        get => $this->id;
    }

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}

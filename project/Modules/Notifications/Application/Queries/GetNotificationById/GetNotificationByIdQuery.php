<?php

namespace Project\Modules\Notifications\Application\Queries\GetNotificationById;

use Project\Base\Application\Queries\QueryInterface;

readonly class GetNotificationByIdQuery implements QueryInterface
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

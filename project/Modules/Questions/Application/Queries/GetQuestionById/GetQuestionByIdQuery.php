<?php

namespace Project\Modules\Questions\Application\Queries\GetQuestionById;

use Project\Base\Application\Queries\QueryInterface;

readonly class GetQuestionByIdQuery implements QueryInterface
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

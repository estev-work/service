<?php

namespace Project\Modules\Questions\Application\Queries\GetQuestionById;

use Project\Base\Application\Queries\QueryHandlerInterface;
use Project\Base\Application\Queries\QueryInterface;
use Project\Modules\Questions\Domain\Question;
use Project\Modules\Questions\Domain\Repositories\QuestionRepositoryInterface;

class GetQuestionByIdHandler implements QueryHandlerInterface
{
    private QuestionRepositoryInterface $repository;

    public function __construct(QuestionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(GetQuestionByIdQuery|QueryInterface $query): ?Question
    {
        return $this->repository->findByQuestionId($query->getId());
    }
}

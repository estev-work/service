<?php

namespace Project\Modules\Activities\Application\Queries\GetActivityById;

use Project\Base\Application\Queries\QueryHandlerInterface;
use Project\Base\Application\Queries\QueryInterface;
use Project\Modules\Activities\Domain\Activity;
use Project\Modules\Activities\Domain\Repositories\ActivityRepositoryInterface;

class GetActivityByIdHandler implements QueryHandlerInterface
{
    private ActivityRepositoryInterface $repository;

    public function __construct(ActivityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(GetActivityByIdQuery|QueryInterface $query): ?Activity
    {
        return $this->repository->findByActivityId($query->getId());
    }
}

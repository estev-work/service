<?php

namespace Project\Modules\Activities\Domain\Repositories;

use Project\Modules\Activities\Domain\Activity;

interface ActivityRepositoryInterface
{
    /**
     * @param Activity $activity
     *
     * @return string|null
     */
    public function saveActivity(Activity $activity): string|null;

    /**
     * @param string $id
     *
     * @return Activity|null
     */
    public function findByActivityId(string $id): ?Activity;

    /**
     * @return array
     */
    public function findAllActivities(): array;
}

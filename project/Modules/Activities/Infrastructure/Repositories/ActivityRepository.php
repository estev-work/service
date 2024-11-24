<?php

namespace Project\Modules\Activities\Infrastructure\Repositories;

use Exception;
use Project\Base\Infrastructure\Repositories\Mysql\MysqlRepository;
use Project\Common\Attributes\Table;
use Project\Modules\Activities\Domain\Activity;
use Project\Modules\Activities\Domain\Repositories\ActivityRepositoryInterface;

#[Table('ma_activities')]
class ActivityRepository extends MysqlRepository implements ActivityRepositoryInterface
{
    /**
     * @throws Exception
     */
    public function saveActivity(Activity $activity): string|null
    {
        try {
            $data = [
                'id' => $activity->id->toString(),
                'title' => $activity->title->value,
                'content' => $activity->content->value,
                'created_at' => $activity->createdAt->format('Y-m-d H:i:s'),
                'updated_at' => $activity->updatedAt?->format('Y-m-d H:i:s'),
            ];
            $query
                = 'INSERT INTO ma_activities (id, title, content, created_at, updated_at) VALUES(:id, :title, :content, :created_at, :updated_at) ON DUPLICATE KEY UPDATE id=:id, title=:title';
            $this->raw($query, $data);
            return $activity->id->toString();
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), $exception->getTrace());
            return null;
        }
    }

    /**
     * @throws Exception
     */
    public function findByActivityId(string $id): ?Activity
    {
        throw new Exception('Not implemented');
    }

    /**
     * @throws Exception
     */
    public function findAllActivities(): array
    {
        throw new Exception('Not implemented');
    }
}

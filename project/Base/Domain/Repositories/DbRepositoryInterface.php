<?php

namespace Project\Base\Domain\Repositories;

interface DbRepositoryInterface
{
    /**
     * @param array $data
     *
     * @return string|false
     */
    public function save(array $data): string|false;

    /**
     * @param string $query
     * @param array  $params
     *
     * @return mixed
     */
    public function raw(string $query, array $params = []): mixed;
}

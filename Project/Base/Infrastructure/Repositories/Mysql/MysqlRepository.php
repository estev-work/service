<?php

namespace Project\Base\Infrastructure\Repositories\Mysql;

use PDO;
use PDOException;
use Project\Base\Infrastructure\Repositories\DbRepositoryInterface;
use Project\Common\Attributes\Table;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use RuntimeException;

class MysqlRepository implements DbRepositoryInterface
{
    protected PDO $connection;
    protected string $table;

    public function __construct(
        PDO $connection,
        protected readonly LoggerInterface $logger,
    ) {
        $this->connection = $connection;
        $this->table = $this->resolveTableName();
    }

    private function resolveTableName(): string
    {
        $reflection = new ReflectionClass($this);
        $attributes = $reflection->getAttributes(Table::class);

        if (!empty($attributes)) {
            /** @var Table $tableAttribute */
            $tableAttribute = $attributes[0]->newInstance();
            return $tableAttribute->name;
        }

        throw new RuntimeException("Table attribute not defined for class " . $reflection->getName());
    }

    public function raw(string $query, array $params = []): mixed
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $queryType = strtoupper(strtok(trim($query), " ")); // Получаем тип запроса

        return match ($queryType) {
            'EXPLAIN', 'DESCRIBE', 'SHOW', 'SELECT' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'INSERT' => $this->connection->lastInsertId(),
            'UPDATE', 'DELETE', 'REPLACE', 'ALTER', 'CREATE', 'DROP' => $stmt->rowCount(),
            'BEGIN', 'COMMIT', 'ROLLBACK' => true,
            default => null,
        };
    }

    public function save(array $data): string|false
    {
        try {
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));

            $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
            $stmt = $this->connection->prepare($query);
            $stmt->execute($data);

            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }
}


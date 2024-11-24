<?php

declare(strict_types=1);

namespace Project\Base\Domain\ValueObjects;

use Project\Base\Domain\Common\Uuid\UuidFactoryInterface;
use Project\Base\Domain\Common\Uuid\UuidInterface;
use Project\Base\Domain\Exceptions\DomainLayerException;

readonly class Identifier
{
    private UuidInterface $uuid;

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Создаёт новый Id с использованием UUID v7.
     */
    public static function create(): self
    {
        try {
            /** @var UuidFactoryInterface $uuidFactory */
            $uuidFactory = resolve(UuidFactoryInterface::class);
            if (!$uuidFactory instanceof UuidFactoryInterface) {
                throw new DomainLayerException('Unable to resolve UuidFactoryInterface.');
            }
        } catch (\Exception $e) {
            throw new DomainLayerException('Error while resolving UuidFactoryInterface: ' . $e->getMessage(), 0, $e);
        }

        return new self($uuidFactory->createV7());
    }

    /**
     * Восстанавливает Id из строки UUID.
     */
    public static function fromString(string $uuid): self
    {
        try {
            /** @var UuidFactoryInterface $uuidFactory */
            $uuidFactory = resolve(UuidFactoryInterface::class);
            if (!$uuidFactory instanceof UuidFactoryInterface) {
                throw new DomainLayerException('Unable to resolve UuidFactoryInterface.');
            }
        } catch (\Exception $e) {
            throw new DomainLayerException('Error while resolving UuidFactoryInterface: ' . $e->getMessage(), 0, $e);
        }

        return new self($uuidFactory->fromString($uuid));
    }

    /**
     * Возвращает UUID в строковом представлении.
     */
    public function toString(): string
    {
        return $this->uuid->toString();
    }

    /**
     * Сравнивает текущий идентификатор с другим.
     */
    public function equals(self $other): bool
    {
        return $this->uuid->toString() === $other->uuid->toString();
    }
}

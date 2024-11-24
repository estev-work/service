<?php

namespace Project\Base\Infrastructure\Common\Uuid;

use Project\Base\Domain\Common\Uuid\UuidInterface;
use Project\Base\Infrastructure\Exceptions\InfrastructureLayerException;

class DomainUuid implements UuidInterface
{
    private string $uuid;

    private function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function fromString(string $uuid): self
    {
        if (!self::isValidUuid($uuid)) {
            throw new InfrastructureLayerException("Invalid UUID string: {$uuid}");
        }

        return new self($uuid);
    }

    public function toString(): string
    {
        return $this->uuid;
    }

    public function isNull(): bool
    {
        return $this->uuid === '00000000-0000-0000-0000-000000000000';
    }

    private static function isValidUuid(string $uuid): bool
    {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-578][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        return preg_match($pattern, $uuid) === 1;
    }
}

<?php

declare(strict_types=1);

namespace Project\Base\Infrastructure\Common\Uuid;

use Project\Base\Domain\Common\Uuid\UuidFactoryInterface;
use Project\Base\Domain\Common\Uuid\UuidInterface;
use Project\Base\Infrastructure\Exceptions\InfrastructureLayerException;

class BaseUuidFactory implements UuidFactoryInterface
{

    public function createV3(UuidInterface $namespace, string $name): UuidInterface
    {
        $namespaceBytes = hex2bin(str_replace('-', '', $namespace->toString()));
        $hash = md5($namespaceBytes . $name, true);

        return DomainUuid::fromString(self::formatUuid($hash, 3));
    }

    public function createV4(): UuidInterface
    {
        try {
            $randomBytes = random_bytes(16);

            $randomBytes[6] = chr((ord($randomBytes[6]) & 0x0f) | 0x40); // Версия 4 (0100)
            $randomBytes[8] = chr((ord($randomBytes[8]) & 0x3f) | 0x80); // Вариант RFC 4122

            return DomainUuid::fromString(self::formatUuid($randomBytes, 4));
        } catch (\Exception $e) {
            throw new InfrastructureLayerException('Failed to generate UUID V4: ' . $e->getMessage(), 0, $e);
        }
    }

    public function createV5(UuidInterface $namespace, string $name): UuidInterface
    {
        $namespaceBytes = hex2bin(str_replace('-', '', $namespace->toString()));
        $hash = sha1($namespaceBytes . $name, true);

        return DomainUuid::fromString(self::formatUuid($hash, 5));
    }

    public function createV7(): UuidInterface
    {
        try {
            $time = (int)(microtime(true) * 1000);
            $timeBytes = pack('J', $time);
            $randomBytes = random_bytes(10);

            $data = $timeBytes . $randomBytes;

            $data[6] = chr((ord($data[6]) & 0x0f) | 0x70);
            $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
            return DomainUuid::fromString(self::formatUuid($data, 7));
        } catch (\Exception $e) {
            throw new InfrastructureLayerException('Failed to generate UUID V7: ' . $e->getMessage(), 0, $e);
        }
    }

    public function fromString(string $uuid): UuidInterface
    {
        return DomainUuid::fromString($uuid);
    }

    private static function formatUuid(string $data, int $version): string
    {
        if ($version < 3 || $version > 7) {
            throw new \InvalidArgumentException("Invalid UUID version: $version");
        }

        // Устанавливаем версию (старшие 4 бита 7-го байта)
        $data[6] = chr((ord($data[6]) & 0x0F) | ($version << 4));

        // Устанавливаем вариант RFC 4122 (старшие 2 бита 9-го байта)
        $data[8] = chr((ord($data[8]) & 0x3F) | 0x80);

        // Форматируем UUID в строку
        $hex = bin2hex($data);

        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12)
        );
    }
}
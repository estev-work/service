<?php

namespace Project\Base\Domain\Common\Uuid;

interface UuidFactoryInterface
{
    public function createV3(UuidInterface $namespace, string $name): UuidInterface;

    public function createV4(): UuidInterface;

    public function createV5(UuidInterface $namespace, string $name): UuidInterface;

    public function createV7(): UuidInterface;

    public function fromString(string $uuid): UuidInterface;
}
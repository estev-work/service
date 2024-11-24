<?php

namespace Project\Base\Domain\Common\Uuid;

interface UuidInterface
{
    public function toString(): string;

    public function isNull(): bool;
}

<?php

declare(strict_types=1);

namespace Project\Base\Domain\Exceptions;

use Throwable;

class DomainLayerException extends \DomainException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
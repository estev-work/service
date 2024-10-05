<?php

namespace Core\Logger;

use Psr\Log\LoggerInterface;

interface AppLoggerInterface extends LoggerInterface
{
    public function __construct(array $config);

    public function setChannel(string $channel): void;
}
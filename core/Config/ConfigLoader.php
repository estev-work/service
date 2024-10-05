<?php

declare(strict_types=1);

namespace Core\Config;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

final class ConfigLoader
{
    public static function load(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("Файл {$filePath} не существует.");
        }

        return Yaml::parseFile($filePath);
    }
}
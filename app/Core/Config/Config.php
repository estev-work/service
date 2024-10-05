<?php

declare(strict_types=1);

namespace App\Core\Config;

use Exception;
use InvalidArgumentException;

final class Config
{
    public static function get(string $key, $default = null)
    {
        try {
            $keys = explode('.', $key);

            $configPath = __DIR__ . '/../../config/' . $keys[0] . '.yaml';

            $config = ConfigLoader::load($configPath);

            array_shift($keys);

            foreach ($keys as $key) {
                if (isset($config[$key])) {
                    $config = $config[$key];
                } else {
                    return $default;
                }
            }

            return $config;
        } catch (InvalidArgumentException $e) {
            throw new \RuntimeException('Invalid configuration: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new \RuntimeException('Error loading configuration: ' . $e->getMessage());
        }
    }
}
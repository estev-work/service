<?php

declare(strict_types=1);

namespace Project\Providers\Services;

use Core\Config\Config;
use Core\DI\DIContainerInterface;
use PDO;
use Project\Providers\ProviderInterface;

final class PDOProvider implements ProviderInterface
{
    public function load(DIContainerInterface $serviceContainer): void
    {
        $serviceContainer->singleton(PDO::class, function () {
            $dsn = "mysql:host=" . Config::get('database.connections.mysql.host')
                . ";dbname=" . Config::get('database.connections.mysql.database')
                . ";port=" . Config::get('database.connections.mysql.port');
            $username = Config::get('database.connections.mysql.username');
            $password = Config::get('database.connections.mysql.password');

            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        });
    }
}
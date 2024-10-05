<?php

namespace Project\Providers\Common;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use PDO;
use Project\Base\Infrastructure\Repositories\DbRepositoryInterface;
use Project\Base\Infrastructure\Repositories\Mysql\MysqlRepository;

class DbServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PDO::class, function ($app) {
            $dsn = "mysql:host=" . config('database.connections.mysql.host')
                . ";dbname=" . config('database.connections.mysql.database')
                . ";port=" . config('database.connections.mysql.port');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');

            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        });
        $this->app->bind(DbRepositoryInterface::class, function ($app) {
            return new MysqlRepository($app->make(PDO::class), Log::channel('database'));
        });
    }

    public function provides(): array
    {
        return [DbRepositoryInterface::class];
    }
}


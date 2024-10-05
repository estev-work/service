<?php

namespace Project\Providers\Common;

use Illuminate\Support\ServiceProvider;
use Project\Base\Application\Bus\CommandBusInterface;
use Project\Base\Application\Bus\EventBusInterface;
use Project\Base\Application\Bus\QueryBusInterface;
use Project\Base\Infrastructure\Bus\CommandBus;
use Project\Base\Infrastructure\Bus\EventBus;
use Project\Base\Infrastructure\Bus\QueryBus;
use Project\Base\Infrastructure\Messaging\MessageBrokerInterface;

class CqrsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CommandBusInterface::class, function ($app) {
            return new CommandBus();
        });

        $this->app->singleton(QueryBusInterface::class, function ($app) {
            return new QueryBus();
        });

        $this->app->singleton(EventBusInterface::class, function ($app) {
            return new EventBus(resolve(MessageBrokerInterface::class));
        });
    }
}

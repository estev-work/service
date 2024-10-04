<?php

namespace Project\Providers\Modules;

use Illuminate\Support\ServiceProvider;
use Project\Base\Application\Bus\CommandBusInterface;
use Project\Base\Application\Bus\EventBusInterface;
use Project\Modules\Questions\Api\QuestionApiInterface;
use Project\Modules\Questions\Application\Services\QuestionApplicationService;
use Project\Modules\Questions\Domain\Repositories\QuestionRepositoryInterface;
use Project\Modules\Questions\Infrastructure\Repositories\QuestionRepository;

class QuestionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(QuestionRepositoryInterface::class, function ($app) {
            return $app->make(QuestionRepository::class);
        });

        $this->app->bind(QuestionApiInterface::class, function ($app) {
            return new QuestionApplicationService(
                $app->make(CommandBusInterface::class),
                $app->make(EventBusInterface::class)
            );
        });
    }
}

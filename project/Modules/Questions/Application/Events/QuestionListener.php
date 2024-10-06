<?php

namespace Project\Modules\Questions\Application\Events;

use Project\Base\Application\Events\EventListenerInterface;
use Project\Base\Domain\Events\EventInterface;
use Project\Common\Attributes\EventHandler;
use Project\Common\Attributes\EventListener;
use Project\Modules\Questions\Application\Services\QuestionApplicationService;

#[EventListener]
readonly class QuestionListener implements EventListenerInterface
{
    private QuestionApplicationService $service;

    public function __construct(QuestionApplicationService $service)
    {
    }

    #[EventHandler(eventName: 'question-created')]
    public function handle(EventInterface $event): bool
    {
        $payload = $event->getPayload();
        return true;
    }
}

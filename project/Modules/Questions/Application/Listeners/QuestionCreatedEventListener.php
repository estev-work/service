<?php

namespace Project\Modules\Questions\Application\Listeners;

use Exception;
use Project\Base\Application\Listeners\EventListenerInterface;
use Project\Modules\Questions\Domain\Question;
use Project\Modules\Questions\Infrastructure\Repositories\QuestionRepository;

readonly class QuestionCreatedEventListener implements EventListenerInterface
{
    private QuestionRepository $repository;

    public function __construct()
    {
        $this->repository = resolve(QuestionRepository::class);
    }

    /**
     * @throws Exception
     */
    public function handle(EventInterface $event): bool
    {
        $payload = $event->getPayload();
        $question = Question::from($payload['id'], $payload['title'], $payload['content'], '', '');
        $id = $this->repository->saveQuestion($question);
        return (bool)$id;
    }
}

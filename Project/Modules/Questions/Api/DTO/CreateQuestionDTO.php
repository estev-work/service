<?php

namespace Project\Modules\Questions\Api\DTO;

readonly class CreateQuestionDTO
{
    /**
     * @param string $title
     * @param string $content
     */
    public function __construct(
        public string $title,
        public string $content
    ) {
    }
}

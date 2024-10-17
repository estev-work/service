<?php

namespace Project\Modules\Notifications\Api\DTO;

readonly class CreateNotificationDTO
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

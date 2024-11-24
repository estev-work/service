<?php

namespace Project\Modules\Activities\Api\DTO;

readonly class CreateActivityDTO
{
    /**
     * @param string $title
     * @param string $content
     */
    public function __construct(
        private(set) string $title,
        private(set) string $content
    ) {
    }
}

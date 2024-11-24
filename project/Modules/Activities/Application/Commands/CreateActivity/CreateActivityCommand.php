<?php

namespace Project\Modules\Activities\Application\Commands\CreateActivity;


use Project\Base\Application\Commands\CommandInterface;

class CreateActivityCommand implements CommandInterface
{
    private(set) string $title = '';
    private(set) string $content = '';

    public function __construct(?string $title = '', ?string $content = '')
    {
        $this->title = $title;
        $this->content = $content;
    }
}

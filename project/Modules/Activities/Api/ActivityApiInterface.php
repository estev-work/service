<?php

namespace Project\Modules\Activities\Api;

use Project\Base\Application\ListenerApiInterface;
use Project\Modules\Activities\Api\DTO\CreateActivityDTO;

interface ActivityApiInterface extends ListenerApiInterface
{
    public function createActivity(CreateActivityDTO $data): string;
}

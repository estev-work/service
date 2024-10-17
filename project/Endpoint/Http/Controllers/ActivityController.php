<?php

namespace Project\Endpoint\Http\Controllers;

use Core\Http\Request;
use Core\Response\JsonResponse;
use Project\Modules\Activities\Api\ActivityApiInterface;
use Project\Modules\Activities\Api\DTO\CreateActivityDTO;

class ActivityController
{
    private ActivityApiInterface $activityApplicationService;

    public function __construct(ActivityApiInterface $activityApplicationService)
    {
        $this->activityApplicationService = $activityApplicationService;
    }

    public function create(Request $request): \Psr\Http\Message\ResponseInterface
    {
        $data = $request->getParsedBody();
        $DTO = new CreateActivityDTO($data['title'], $data['content']);

        $this->activityApplicationService->createActivity($DTO);
        return JsonResponse::send(
            [
                'success' => true,
            ],
            202
        );
    }
}

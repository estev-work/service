<?php

declare(strict_types=1);

namespace Core\Response;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

final class EmptyResponse extends BaseResponse
{
    public function createResponse(int $code = 200, mixed $reasonPhrase = ''): ResponseInterface
    {
        return new Response($code);
    }
}
<?php

declare(strict_types=1);

namespace Core\Response;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BaseResponse implements ResponseFactoryInterface
{
    abstract function createResponse(int $code = 200, mixed $reasonPhrase = ''): ResponseInterface;

    public static function send(mixed $data = null, int $code = 200): ResponseInterface
    {
        return (new static())->createResponse($code, $data);
    }
}
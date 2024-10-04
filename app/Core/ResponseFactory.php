<?php

declare(strict_types=1);

namespace App\Core;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

abstract class ResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200, mixed $reasonPhrase = ''): ResponseInterface
    {
        $response = self::send($code, $reasonPhrase);
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        http_response_code($response->getStatusCode());
        return $response;
    }

    abstract static function send(int $code, string $reasonPhrase): ResponseInterface;
}
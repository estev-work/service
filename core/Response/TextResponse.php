<?php

declare(strict_types=1);

namespace Core\Response;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

final class TextResponse extends BaseResponse
{
    public function createResponse(int $code = 200, mixed $reasonPhrase = ''): ResponseInterface
    {
        $response = new Response(
            $code,
            ['Content-Type' => 'text/plain'],
            $reasonPhrase
        );

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        return $response;
    }
}
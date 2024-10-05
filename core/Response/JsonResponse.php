<?php

declare(strict_types=1);

namespace Core\Response;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

final class JsonResponse extends BaseResponse
{
    public function createResponse(int $code = 200, mixed $reasonPhrase = ''): ResponseInterface
    {
        if (is_array($reasonPhrase)) {
            $reasonPhrase = json_encode($reasonPhrase, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Ошибка при создании JSON: ' . json_last_error_msg());
        }
        $response = new Response(
            $code,
            ['Content-Type' => 'application/json'],
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
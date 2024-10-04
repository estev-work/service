<?php

declare(strict_types=1);

namespace App\Core\Response;

use App\Core\ResponseFactory;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

final class JsonResponse extends ResponseFactory
{
    public static function send(int $code = 200, mixed $reasonPhrase = ''): ResponseInterface
    {
        if (is_array($reasonPhrase)) {
            $reasonPhrase = json_encode($reasonPhrase, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Ошибка при создании JSON: ' . json_last_error_msg());
        }

        return new Response(
            $code,
            ['Content-Type' => 'application/json'],
            $reasonPhrase
        );
    }
}
<?php

declare(strict_types=1);

namespace App\Core\Response;

use App\Core\ResponseFactory;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

final class TextResponse extends ResponseFactory
{
    public static function send(int $code = 200, mixed $reasonPhrase = ''): ResponseInterface
    {
        return new Response(
            $code,
            ['Content-Type' => 'text/plain'],
            $reasonPhrase
        );
    }
}
<?php

declare(strict_types=1);

namespace Core\Controllers;

use Core\Http\Request;
use Core\Response\EmptyResponse;
use Core\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

final class QuestionController
{
    public function index(): ResponseInterface
    {
        return EmptyResponse::send();
    }

    public function show(Request $request, string $id): ResponseInterface
    {
        return EmptyResponse::send();
    }

    public function update(Request $request, string $id): ResponseInterface
    {
        $query = $request->getQueryParams();
        $body = $request->getParsedBody();
        return JsonResponse::send(['result' => 'success', 'id' => $id, 'query' => $query, 'body' => $body]);
    }
}
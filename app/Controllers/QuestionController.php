<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Http\Request;
use App\Core\Response\EmptyResponse;
use App\Core\Response\JsonResponse;
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
        return JsonResponse::send(200, ['result' => 'success', 'id' => $id, 'query' => $query, 'body' => $body]);
    }
}
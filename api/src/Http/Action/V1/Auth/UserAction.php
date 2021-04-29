<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth;

use App\Http\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UserAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([
            'id' => $request->getAttribute('oauth_user_id'),
        ]);
    }
}

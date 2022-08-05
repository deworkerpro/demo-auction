<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth;

use App\Http\Exception\UnauthorizedHttpException;
use App\Http\Middleware\Auth\Authenticate;
use App\Http\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class UserAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly NormalizerInterface $normalizer
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $identity = Authenticate::identity($request);

        if ($identity === null) {
            throw new UnauthorizedHttpException($request);
        }

        return new JsonResponse($this->normalizer->normalize($identity));
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Newsletter;

use App\Http\Exception\UnauthorizedHttpException;
use App\Http\Middleware\Auth\Authenticate;
use App\Http\Response\JsonResponse;
use App\Newsletter\Query\Subscription\Fetcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ShowAction implements RequestHandlerInterface
{
    public function __construct(private readonly Fetcher $fetcher) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $identity = Authenticate::identity($request);

        if ($identity === null) {
            throw new UnauthorizedHttpException($request);
        }

        $subscription = $this->fetcher->fetch($identity->id);

        return new JsonResponse([
            'subscribed' => $subscription !== null,
        ]);
    }
}

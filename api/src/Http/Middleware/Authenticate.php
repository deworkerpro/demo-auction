<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Authenticate implements MiddlewareInterface
{
    private ResourceServer $server;
    private ResponseFactoryInterface $response;

    public function __construct(ResourceServer $server, ResponseFactoryInterface $response)
    {
        $this->server = $server;
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $request = $this->server->validateAuthenticatedRequest($request);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($this->response->createResponse());
        }

        return $handler->handle($request);
    }
}

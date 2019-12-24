<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use stdClass;

class HomeAction implements RequestHandlerInterface
{
    private ResponseFactoryInterface $factory;

    public function __construct(ResponseFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->factory->createResponse();
        return Http::json($response, new stdClass());
    }
}

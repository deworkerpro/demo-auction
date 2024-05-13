<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth\Join;

use App\Auth\Command\JoinByEmail\Request\Command;
use App\Auth\Command\JoinByEmail\Request\Handler;
use App\Http\Response\EmptyResponse;
use App\Serializer\Denormalizer;
use App\Validator\Validator;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class Request implements RequestHandlerInterface
{
    public function __construct(
        private Denormalizer $denormalizer,
        private Validator $validator,
        private Handler $handler
    ) {}

    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $command = $this->denormalizer->denormalize($request->getParsedBody(), Command::class);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(201);
    }
}

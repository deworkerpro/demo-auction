<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Newsletter;

use App\Http\Exception\UnauthorizedHttpException;
use App\Http\Middleware\Auth\Authenticate;
use App\Http\Response\EmptyResponse;
use App\Newsletter\Command\Unsubscribe\Command;
use App\Newsletter\Command\Unsubscribe\Handler;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UnsubscribeAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly Validator $validator,
        private readonly Handler $handler
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $identity = Authenticate::identity($request);

        if ($identity === null) {
            throw new UnauthorizedHttpException($request);
        }

        $command = new Command($identity->id);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse();
    }
}

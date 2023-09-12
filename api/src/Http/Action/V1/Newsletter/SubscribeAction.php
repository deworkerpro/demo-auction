<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Newsletter;

use App\Auth\Query\FindContactById\Fetcher;
use App\Http\Exception\NotFoundHttpException;
use App\Http\Exception\UnauthorizedHttpException;
use App\Http\Middleware\Auth\Authenticate;
use App\Http\Response\EmptyResponse;
use App\Newsletter\Command\Subscribe\Command;
use App\Newsletter\Command\Subscribe\Handler;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SubscribeAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly Validator $validator,
        private readonly Handler $handler,
        private readonly Fetcher $contacts
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $identity = Authenticate::identity($request);

        if ($identity === null) {
            throw new UnauthorizedHttpException($request);
        }

        $contact = $this->contacts->fetch($identity->id);

        if ($contact === null) {
            throw new NotFoundHttpException($request);
        }

        $command = new Command($contact->id, $contact->email);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(201);
    }
}

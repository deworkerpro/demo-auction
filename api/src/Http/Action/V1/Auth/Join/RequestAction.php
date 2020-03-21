<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth\Join;

use App\Auth\Command\JoinByEmail\Request\Command;
use App\Auth\Command\JoinByEmail\Request\Handler;
use App\Http\EmptyResponse;
use App\Http\JsonResponse;
use App\Http\Validator\ValidationException;
use App\Http\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

class RequestAction implements RequestHandlerInterface
{
    private Handler $handler;
    private Validator $validator;

    public function __construct(Handler $handler, Validator $validator)
    {
        $this->handler = $handler;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @psalm-var array{email:?string, password:?string} $data
         */
        $data = $request->getParsedBody();

        $command = new Command();
        $command->email = $data['email'] ?? '';
        $command->password = $data['password'] ?? '';

        try {
            $this->validator->validate($command);
        } catch (ValidationException $exception) {
            $errors = [];
            /** @var ConstraintViolationInterface $violation */
            foreach ($exception->getViolations() as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 422);
        }

        $this->handler->handle($command);

        return new EmptyResponse(201);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth\Join;

use App\Auth\Command\JoinByEmail\Request\Command;
use App\Auth\Command\JoinByEmail\Request\Handler;
use App\Http\Response\EmptyResponse;
use App\Http\Response\JsonResponse;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly DenormalizerInterface $denormalizer,
        private readonly Validator $validator,
        private readonly Handler $handler
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            /** @var Command $command */
            $command = $this->denormalizer->denormalize($request->getParsedBody(), Command::class);
        } catch (NotNormalizableValueException $exception) {
            return new JsonResponse([
                'errors' => [
                    (string)$exception->getPath() => sprintf(
                        'The type must be one of "%s" ("%s" given).',
                        implode(', ', (array)$exception->getExpectedTypes()),
                        (string)$exception->getCurrentType()
                    ),
                ],
            ], 422);
        }

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(201);
    }
}

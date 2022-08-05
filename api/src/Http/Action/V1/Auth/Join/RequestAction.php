<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth\Join;

use App\Auth\Command\JoinByEmail\Request\Command;
use App\Auth\Command\JoinByEmail\Request\Handler;
use App\Http\Response\EmptyResponse;
use App\Validator\ValidationException;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

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
            $command = $this->denormalizer->denormalize($request->getParsedBody(), Command::class, null, [
                DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
            ]);
        } catch (PartialDenormalizationException $exception) {
            $violations = new ConstraintViolationList();
            /** @var NotNormalizableValueException $error */
            foreach ($exception->getErrors() as $error) {
                $message = sprintf(
                    'The type must be one of "%s" ("%s" given).',
                    implode(', ', (array)$error->getExpectedTypes()),
                    (string)$error->getCurrentType()
                );
                $parameters = [];
                if ($error->canUseMessageForUser()) {
                    $parameters['hint'] = $error->getMessage();
                }
                $violations->add(new ConstraintViolation($message, '', $parameters, null, $error->getPath(), null));
            }

            throw new ValidationException($violations);
        }

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(201);
    }
}

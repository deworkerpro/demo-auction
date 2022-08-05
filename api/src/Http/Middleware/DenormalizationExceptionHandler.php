<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Validator\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

final class DenormalizationExceptionHandler implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
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
    }
}

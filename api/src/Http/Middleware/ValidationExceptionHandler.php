<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Response\JsonResponse;
use App\Validator\ValidationException;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidationExceptionHandler implements MiddlewareInterface
{
    #[Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $exception) {
            return new JsonResponse([
                'errors' => self::errorsArray($exception->getViolations()),
            ], 422);
        }
    }

    private static function errorsArray(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            if (!\array_key_exists($violation->getPropertyPath(), $errors)) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
        }
        return $errors;
    }
}

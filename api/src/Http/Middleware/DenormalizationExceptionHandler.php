<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Validator\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
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
        } catch (ExtraAttributesException $exception) {
            $violations = new ConstraintViolationList();
            /** @var string $attribute */
            foreach ($exception->getExtraAttributes() as $attribute) {
                $violations->add(self::attributeToViolation($attribute));
            }
            throw new ValidationException($violations);
        } catch (NotNormalizableValueException $exception) {
            $violations = new ConstraintViolationList();
            $violations->add(self::errorToViolation($exception));
            throw new ValidationException($violations);
        } catch (PartialDenormalizationException $exception) {
            $violations = new ConstraintViolationList();
            /** @var NotNormalizableValueException $error */
            foreach ($exception->getErrors() as $error) {
                $violations->add(self::errorToViolation($error));
            }
            throw new ValidationException($violations);
        }
    }

    private static function attributeToViolation(string $attribute): ConstraintViolation
    {
        return new ConstraintViolation('The attribute is not allowed.', '', [], null, $attribute, null);
    }

    private static function errorToViolation(NotNormalizableValueException $exception): ConstraintViolation
    {
        $message = sprintf(
            'The type must be one of "%s" ("%s" given).',
            implode(', ', (array)$exception->getExpectedTypes()),
            (string)$exception->getCurrentType()
        );
        $parameters = [];
        if ($exception->canUseMessageForUser()) {
            $parameters['hint'] = $exception->getMessage();
        }
        return new ConstraintViolation($message, '', $parameters, null, $exception->getPath(), null);
    }
}

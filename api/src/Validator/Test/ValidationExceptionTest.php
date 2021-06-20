<?php

declare(strict_types=1);

namespace App\Validator\Test;

use App\Validator\ValidationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @covers \App\Validator\ValidationException
 *
 * @internal
 */
final class ValidationExceptionTest extends TestCase
{
    public function testValid(): void
    {
        $exception = new ValidationException(
            $violations = new ConstraintViolationList()
        );

        self::assertEquals('Invalid input.', $exception->getMessage());
        self::assertEquals($violations, $exception->getViolations());
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Test\Unit\Validator;

use App\Http\Validator\ValidationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @covers ValidationException
 */
class ValidationExceptionTest extends TestCase
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

<?php

declare(strict_types=1);

namespace App\Http\Test\Unit\Validator;

use App\Http\Validator\ValidationException;
use App\Http\Validator\Validator;
use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @covers Validator
 */
class ValidatorTest extends TestCase
{
    public function testValid(): void
    {
        $command = new stdClass();

        $origin = $this->createMock(ValidatorInterface::class);
        $origin->expects($this->once())->method('validate')
            ->with($this->equalTo($command))
            ->willReturn(new ConstraintViolationList());

        $validator = new Validator($origin);

        $validator->validate($command);
    }

    public function testNotValid(): void
    {
        $command = new stdClass();

        $origin = $this->createMock(ValidatorInterface::class);
        $origin->expects($this->once())->method('validate')
            ->with($this->equalTo($command))
            ->willReturn($violations = new ConstraintViolationList([
                $this->createMock(ConstraintViolation::class),
            ]));

        $validator = new Validator($origin);

        try {
            $validator->validate($command);
            self::fail('Expected exception is not thrown');
        } catch (Exception $exception) {
            self::assertInstanceOf(ValidationException::class, $exception);
            /** @var ValidationException $exception */
            self::assertEquals($violations, $exception->getViolations());
        }
    }
}

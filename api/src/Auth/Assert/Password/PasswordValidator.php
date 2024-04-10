<?php

declare(strict_types=1);

namespace App\Auth\Assert\Password;

use Override;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class PasswordValidator extends ConstraintValidator
{
    #[Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Password) {
            throw new UnexpectedTypeException($constraint, Password::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (preg_match('/\s/', $value)) {
            $this->context->buildViolation('Password should not contain any white space.')->addViolation();
        }

        if (!preg_match('/[A-ZА-ЯЁ]/u', $value)) {
            $this->context->buildViolation('Password should contain at least one capital letter.')->addViolation();
        }

        if (!preg_match('/[a-zа-яё]/u', $value)) {
            $this->context->buildViolation('Password should contain at least one small letter.')->addViolation();
        }

        if (!preg_match('/\d/', $value)) {
            $this->context->buildViolation('Password should contain at least one digit.')->addViolation();
        }

        if (!preg_match('/\W/', $value) && !preg_match('/_/', $value)) {
            $this->context->buildViolation('Password should contain at least one special character.')->addViolation();
        }
    }
}

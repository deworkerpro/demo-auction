<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class Validator
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validate(object $object): void
    {
        $violations = $this->validator->validate($object);
        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }
    }
}

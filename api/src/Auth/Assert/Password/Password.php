<?php

declare(strict_types=1);

namespace App\Auth\Assert\Password;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Password extends Constraint {}

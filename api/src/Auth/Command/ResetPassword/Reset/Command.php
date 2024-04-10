<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Reset;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Command
{
    public function __construct(
        #[Assert\NotBlank]
        public string $token = '',
        #[Assert\NotBlank]
        #[Assert\Length(min: 6)]
        public string $password = ''
    ) {}
}

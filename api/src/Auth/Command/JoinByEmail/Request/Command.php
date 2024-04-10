<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email = '',
        #[Assert\NotBlank]
        #[Assert\Length(min: 6)]
        public string $password = ''
    ) {}
}

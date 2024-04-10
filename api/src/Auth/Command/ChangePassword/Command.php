<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangePassword;

use App\Auth\Assert\Password\Password;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class Command
{
    public function __construct(
        #[Assert\NotBlank]
        public string $id = '',
        #[Assert\NotBlank]
        public string $current = '',
        #[Assert\NotBlank]
        #[Assert\Length(min: 8)]
        #[Password]
        public string $new = ''
    ) {}
}

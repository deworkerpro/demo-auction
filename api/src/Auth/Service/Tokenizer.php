<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Token;
use DateTimeImmutable;

interface Tokenizer
{
    public function generate(DateTimeImmutable $date): Token;
}

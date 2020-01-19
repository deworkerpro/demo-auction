<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\Email;

interface JoinConfirmationSender
{
    public function send(Email $email, Token $token): void;
}

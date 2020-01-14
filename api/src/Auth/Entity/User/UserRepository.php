<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

interface UserRepository
{
    public function hasByEmail(Email $email): bool;
    public function findByJoinConfirmToken(string $token): ?User;
    public function add(User $user): void;
}

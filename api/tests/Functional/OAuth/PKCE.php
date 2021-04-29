<?php

declare(strict_types=1);

namespace Test\Functional\OAuth;

final class PKCE
{
    public static function verifier(): string
    {
        $bytes = random_bytes(64);
        return rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
    }

    public static function challenge(string $verifier): string
    {
        $challenge_bytes = hash('sha256', $verifier, true);
        return rtrim(strtr(base64_encode($challenge_bytes), '+/', '-_'), '=');
    }
}

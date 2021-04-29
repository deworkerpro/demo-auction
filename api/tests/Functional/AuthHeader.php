<?php

declare(strict_types=1);

namespace Test\Functional;

use App\OAuth\Entity\AccessToken;
use App\OAuth\Entity\Client;
use App\OAuth\Entity\Scope;
use DateTimeImmutable;
use League\OAuth2\Server\CryptKey;
use function App\env;

final class AuthHeader
{
    public static function for(string $userId, string $role): string
    {
        $token = new AccessToken(
            new Client(
                identifier: 'frontend',
                name: 'Auction',
                redirectUri: 'http://localhost:8080/oauth'
            ),
            [new Scope('common')],
        );
        $token->setIdentifier(bin2hex(random_bytes(40)));
        $token->setExpiryDateTime(new DateTimeImmutable('+1000 years'));
        $token->setUserIdentifier($userId);
        $token->setUserRole($role);
        $token->setPrivateKey(new CryptKey(env('JWT_PRIVATE_KEY_PATH'), null, false));

        return 'Bearer ' . $token;
    }
}

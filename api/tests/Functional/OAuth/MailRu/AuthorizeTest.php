<?php

declare(strict_types=1);

namespace Test\Functional\OAuth\MailRu;

use Test\Functional\OAuth\PKCE;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class AuthorizeTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::html(
            'GET',
            '/authorize?' . http_build_query([
                'response_type' => 'code',
                'client_id' => 'frontend',
                'code_challenge' => PKCE::challenge(PKCE::verifier()),
                'code_challenge_method' => 'S256',
                'redirect_uri' => 'http://localhost/oauth',
                'scope' => 'common',
                'state' => 'sTaTe',
                'provider' => 'mailru',
            ])
        ));

        self::assertSame(302, $response->getStatusCode());
        self::assertNotEmpty($location = $response->getHeaderLine('Location'));

        [$url, $query] = explode('?', $location);

        self::assertSame('http://wiremock.localhost/oauth.mail.ru/login', $url);

        parse_str($query, $params);

        self::assertSame([
            'client_id' => 'app',
            'redirect_uri' => 'http://api.localhost/oauth/mailru',
            'response_type' => 'code',
            'scope' => 'userinfo',
            'state' => 'sTaTe',
        ], $params);
    }
}

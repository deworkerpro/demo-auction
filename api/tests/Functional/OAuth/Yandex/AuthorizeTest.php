<?php

declare(strict_types=1);

namespace Test\Functional\OAuth\Yandex;

use Dflydev\FigCookies\SetCookie;
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
                'provider' => 'yandex',
            ])
        ));

        self::assertSame(302, $response->getStatusCode());
        self::assertNotEmpty($location = $response->getHeaderLine('Location'));

        [$url, $query] = explode('?', $location);

        self::assertSame('http://wiremock.localhost/oauth.yandex.ru/authorize', $url);

        parse_str($query, $params);

        self::assertSame([
            'client_id' => 'app',
            'redirect_uri' => 'http://api.localhost/oauth/yandex',
            'response_type' => 'code',
            'scope' => 'login:email',
            'state' => 'sTaTe',
        ], $params);

        self::assertNotEmpty($setCookie = $response->getHeaderLine('Set-Cookie'));

        $cookie = SetCookie::fromSetCookieString($setCookie);

        self::assertSame('auth_query', $cookie->getName());
        self::assertSame('/oauth', $cookie->getPath());
    }
}

<?php

declare(strict_types=1);

namespace Test\Functional\OAuth\Yandex;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Override;
use Test\Functional\Json;
use Test\Functional\OAuth\PKCE;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class CallbackTest extends WebTestCase
{
    use ArraySubsetAsserts;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            CallbackFixture::class,
        ]);
    }

    public function testWithoutCookies(): void
    {
        $this->oAuthYandex()->initAuthFlow('code', '1111', 'new@app.test');

        $response = $this->app()->handle(self::html('GET', '/oauth/yandex?' . http_build_query([
            'code' => 'code',
            'state' => 'sTaTe',
        ])));

        self::assertSame(400, $response->getStatusCode());
        self::assertJson($content = (string)$response->getBody());

        self::assertArraySubset([
            'error' => 'unsupported_grant_type',
        ], Json::decode($content));
    }

    public function testNew(): void
    {
        $this->oAuthYandex()->initAuthFlow('code', '1111', 'new@app.test');

        $response = $this->app()->handle(self::html('GET', '/oauth/yandex?' . http_build_query([
            'code' => 'code',
            'state' => 'sTaTe',
        ]))->withCookieParams(['auth_query' => json_encode(self::createPrevRequestParams())]));

        self::assertSame(302, $response->getStatusCode());
        self::assertNotEmpty($location = $response->getHeaderLine('Location'));

        [$url, $query] = explode('?', $location);
        self::assertSame('http://localhost/oauth', $url);

        parse_str($query, $data);
        self::assertArraySubset(['state' => 'sTaTe'], $data);
        self::assertArrayHasKey('code', $data);
        self::assertNotEmpty($data['code']);
    }

    public function testExistingNetwork(): void
    {
        $this->oAuthYandex()->initAuthFlow('code', '13333333', 'other@app.test');

        $response = $this->app()->handle(self::html('GET', '/oauth/yandex?' . http_build_query([
            'code' => 'code',
            'state' => 'sTaTe',
        ]))->withCookieParams(['auth_query' => json_encode(self::createPrevRequestParams())]));

        self::assertSame(302, $response->getStatusCode());
        self::assertNotEmpty($location = $response->getHeaderLine('Location'));

        [$url, $query] = explode('?', $location);
        self::assertSame('http://localhost/oauth', $url);

        parse_str($query, $data);
        self::assertArraySubset(['state' => 'sTaTe'], $data);
        self::assertArrayHasKey('code', $data);
        self::assertNotEmpty($data['code']);
    }

    public function testExistingEmail(): void
    {
        $this->oAuthYandex()->initAuthFlow('code', '1111', 'active@app.test');

        $response = $this->app()->handle(self::html('GET', '/oauth/yandex?' . http_build_query([
            'code' => 'code',
            'state' => 'sTaTe',
        ]))->withCookieParams(['auth_query' => json_encode(self::createPrevRequestParams())]));

        self::assertSame(302, $response->getStatusCode());
        self::assertNotEmpty($location = $response->getHeaderLine('Location'));

        [$url, $query] = explode('?', $location);
        self::assertSame('http://localhost/oauth', $url);

        parse_str($query, $data);
        self::assertArraySubset(['state' => 'sTaTe'], $data);
        self::assertArrayHasKey('code', $data);
        self::assertNotEmpty($data['code']);
    }

    public function testExistingEmailWait(): void
    {
        $this->oAuthYandex()->initAuthFlow('code', '1111', 'wait@app.test');

        $response = $this->app()->handle(self::html('GET', '/oauth/yandex?' . http_build_query([
            'code' => 'code',
            'state' => 'sTaTe',
        ]))->withCookieParams(['auth_query' => json_encode(self::createPrevRequestParams())]));

        self::assertSame(302, $response->getStatusCode());
        self::assertNotEmpty($location = $response->getHeaderLine('Location'));

        [$url, $query] = explode('?', $location);
        self::assertSame('http://localhost/oauth', $url);

        parse_str($query, $data);
        self::assertArraySubset(['state' => 'sTaTe'], $data);
        self::assertArrayHasKey('code', $data);
        self::assertNotEmpty($data['code']);
    }

    public function testInvalidState(): void
    {
        $this->oAuthYandex()->initAuthFlow('code', '1111', 'new@app.test');

        $response = $this->app()->handle(self::html('GET', '/oauth/yandex?' . http_build_query([
            'code' => 'code',
            'state' => 'InvalidState',
        ]))->withCookieParams(['auth_query' => json_encode(self::createPrevRequestParams())]));

        self::assertSame(400, $response->getStatusCode());
        self::assertJson($content = (string)$response->getBody());

        self::assertArraySubset([
            'error' => 'invalid_request',
            'hint' => 'Check the `state` parameter',
        ], Json::decode($content));
    }

    private function createPrevRequestParams(): array
    {
        return [
            'protocol' => 'oauth2',
            'response_type' => 'code',
            'client_id' => 'frontend',
            'code_challenge' => PKCE::challenge(PKCE::verifier()),
            'code_challenge_method' => 'S256',
            'redirect_uri' => 'http://localhost/oauth',
            'scope' => 'common',
            'state' => 'sTaTe',
        ];
    }
}

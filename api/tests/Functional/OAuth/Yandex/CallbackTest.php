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

    public function testWithoutCookies(): never
    {
        self::markTestIncomplete();

        $response = $this->app()->handle(self::html('GET', '/oauth/yandex?' . http_build_query([
            'code' => 'InvalidCode',
            'state' => 'sTaTe',
        ])));

        self::assertSame(400, $response->getStatusCode());
        self::assertJson($content = (string)$response->getBody());

        self::assertArraySubset([
            'error' => 'unsupported_grant_type',
        ], Json::decode($content));
    }

    public function testNew(): never
    {
        self::markTestIncomplete();

        $response = $this->app()->handle(self::html('GET', '/oauth/yandex?' . http_build_query([
            'code' => 'CodeNew',
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

    public function testExistingNetwork(): never
    {
        self::markTestIncomplete();

        $response = $this->app()->handle(self::html('GET', '/oauth/yandex?' . http_build_query([
            'code' => 'CodeExistingNetwork',
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

    public function testExistingEmail(): never
    {
        self::markTestIncomplete();

        $response = $this->app()->handle(self::html('GET', '/oauth/yandex?' . http_build_query([
            'code' => 'CodeExistingEmail',
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

    public function testExistingEmailWait(): never
    {
        self::markTestIncomplete();

        $response = $this->app()->handle(self::html('GET', '/oauth/yandex?' . http_build_query([
            'code' => 'CodeExistingEmailWait',
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

    public function testInvalidState(): never
    {
        self::markTestIncomplete();

        $response = $this->app()->handle(self::html('GET', '/oauth/yandex?' . http_build_query([
            'code' => 'CodeNew',
            'state' => 'InvalidState',
        ]))->withCookieParams(['auth_query' => json_encode(self::createPrevRequestParams())]));

        self::assertSame(400, $response->getStatusCode());
        self::assertJson($content = (string)$response->getBody());

        self::assertArraySubset([
            'state' => 'sTaTe',
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

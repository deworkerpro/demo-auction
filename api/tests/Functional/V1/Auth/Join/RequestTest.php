<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Join;

use Override;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class RequestTest extends WebTestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            RequestFixture::class,
        ]);
    }

    public function testMethod(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/auth/join'));

        self::assertSame(405, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $this->mailer()->clear();

        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => 'new-user@app.test',
            'password' => 'n9w#pasS_word',
        ]));

        self::assertSame(201, $response->getStatusCode());
        self::assertSame('', (string)$response->getBody());

        self::assertTrue($this->mailer()->hasEmailSentTo('new-user@app.test'));
    }

    public function testExisting(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => 'existing@app.test',
            'password' => 'n9w#pasS_word',
        ]));

        self::assertSame(409, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertSame([
            'message' => 'User already exists.',
        ], Json::decode($body));
    }

    public function testExistingLang(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => 'existing@app.test',
            'password' => 'n9w#pasS_word',
        ])->withHeader('Accept-Language', 'ru'));

        self::assertSame(409, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertSame([
            'message' => 'Пользователь уже существует.',
        ], $data);
    }

    public function testEmptyBody(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', []));

        self::assertSame(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertSame([
            'errors' => [
                'email' => 'This value should not be blank.',
                'password' => 'This value should not be blank.',
            ],
        ], Json::decode($body));
    }

    public function testEmptyFields(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => '',
            'password' => '',
        ]));

        self::assertSame(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertSame([
            'errors' => [
                'email' => 'This value should not be blank.',
                'password' => 'This value should not be blank.',
            ],
        ], Json::decode($body));
    }

    public function testNotExistingFields(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => 'existing@app.test',
            'password' => 'n9w#pasS_word',
            'age' => 42,
        ]));

        self::assertSame(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertSame([
            'errors' => [
                'age' => 'The attribute is not allowed.',
            ],
        ], Json::decode($body));
    }

    public function testIncorrectFormat(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => true,
            'password' => 42,
        ]));

        self::assertSame(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertSame([
            'errors' => [
                'email' => 'The type must be one of "string" ("bool" given).',
                'password' => 'The type must be one of "string" ("int" given).',
            ],
        ], Json::decode($body));
    }

    public function testNotValid(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => 'not-email',
            'password' => 'new',
        ]));

        self::assertSame(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertSame([
            'errors' => [
                'email' => 'This value is not a valid email address.',
                'password' => 'This value is too short. It should have 8 characters or more.',
            ],
        ], Json::decode($body));
    }

    public function testNotValidPassword(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => 'not-email',
            'password' => 'new-password',
        ]));

        self::assertSame(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertSame([
            'errors' => [
                'email' => 'This value is not a valid email address.',
                'password' => 'Password should contain at least one capital letter.',
            ],
        ], Json::decode($body));
    }

    public function testNotValidLang(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => 'not-email',
            'password' => '',
        ])->withHeader('Accept-Language', 'es;q=0.9, ru;q=0.8, *;q=0.5'));

        self::assertSame(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertSame([
            'errors' => [
                'email' => 'Значение адреса электронной почты недопустимо.',
                'password' => 'Значение не должно быть пустым.',
            ],
        ], $data);
    }
}

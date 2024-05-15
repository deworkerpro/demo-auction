<?php

declare(strict_types=1);

namespace Test\Functional;

final readonly class OAuthYandexClient
{
    public function __construct(
        private WiremockClient $wiremock
    ) {}

    public function initAuthFlow(string $code, string $id, string $email): void
    {
        $token = base64_encode($code . '-' . $id . '-' . $email);

        $this->wiremock->addMapping([
            'request' => [
                'method' => 'POST',
                'url' => '/oauth.yandex.ru/token',
                'headers' => [
                    'Accept' => [
                        'equalTo' => 'application/json',
                    ],
                ],
                'bodyPatterns' => [
                    ['matches' => '.*client_id=app&.*'],
                    ['matches' => '.*client_secret=yandex-secret&.*'],
                    ['matches' => '.*grant_type=authorization_code&.*'],
                    ['matches' => '.*code=' . $code . '$'],
                ],
            ],
            'response' => [
                'status' => 200,
                'jsonBody' => [
                    'access_token' => $token,
                    'expires_in' => 3599,
                    'token_type' => 'bearer',
                    'scope' => 'login:email',
                    'refresh_token' => 'qAJ1FJQ7NPVm7p',
                ],
            ],
        ]);

        $this->wiremock->addMapping([
            'request' => [
                'method' => 'GET',
                'url' => '/login.yandex.ru/info',
                'headers' => [
                    'Authorization' => [
                        'equalTo' => 'bearer ' . $token,
                    ],
                    'Accept' => [
                        'equalTo' => 'application/json',
                    ],
                ],
            ],
            'response' => [
                'status' => 200,
                'jsonBody' => [
                    'id' => $id,
                    'default_email' => $email,
                ],
            ],
        ]);
    }
}

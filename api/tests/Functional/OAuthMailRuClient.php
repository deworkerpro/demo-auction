<?php

declare(strict_types=1);

namespace Test\Functional;

final readonly class OAuthMailRuClient
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
                'url' => '/oauth.mail.ru/token',
                'headers' => [
                    'Accept' => [
                        'equalTo' => 'application/json',
                    ],
                ],
                'bodyPatterns' => [
                    ['matches' => '.*client_id=app&.*'],
                    ['matches' => '.*client_secret=mailru-secret&.*'],
                    ['matches' => '.*redirect_uri=http%3A%2F%2Fapi.localhost%2Foauth%2Fmailru&.*'],
                    ['matches' => '.*grant_type=authorization_code&.*'],
                    ['matches' => '.*code=' . $code . '$'],
                ],
            ],
            'response' => [
                'status' => 200,
                'jsonBody' => [
                    'access_token' => $token,
                    'expires_in' => 3599,
                    'refresh_token' => 'qAJ1FJQ7NPVm7p',
                ],
            ],
        ]);

        $this->wiremock->addMapping([
            'request' => [
                'method' => 'GET',
                'urlPath' => '/oauth.mail.ru/userinfo',
                'queryParameters' => [
                    'access_token' => [
                        'equalTo' => $token,
                    ],
                ],
                'headers' => [
                    'Accept' => [
                        'equalTo' => 'application/json',
                    ],
                ],
            ],
            'response' => [
                'status' => 200,
                'jsonBody' => [
                    'id' => $id,
                    'email' => $email,
                ],
            ],
        ]);
    }
}

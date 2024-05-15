<?php

declare(strict_types=1);

use App\OAuthClient\OAuthClient;
use App\OAuthClient\Provider\MailRu;
use App\OAuthClient\Provider\Provider;
use App\OAuthClient\Provider\Yandex;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;

use function App\env;

return [
    OAuthClient::class => static fn (ContainerInterface $container): OAuthClient => new OAuthClient([
        $container->get(Yandex::class),
        $container->get(MailRu::class),
    ]),

    Yandex::class => static function (ContainerInterface $container): Provider {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     auth_url: string,
         *     token_url: string,
         *     api_url: string,
         *     client_id: string,
         *     client_secret: string,
         *     callback_url: string
         * } $config
         */
        $config = $container->get('config')['oauth-client']['yandex'];

        return new Yandex(
            client: new Client(),
            authUrl: $config['auth_url'],
            callbackUrl: $config['callback_url'],
            tokenUrl: $config['token_url'],
            apiUrl: $config['api_url'],
            clientId: $config['client_id'],
            clientSecret: $config['client_secret'],
        );
    },

    MailRu::class => static function (ContainerInterface $container): Provider {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     auth_url: string,
         *     token_url: string,
         *     api_url: string,
         *     client_id: string,
         *     client_secret: string,
         *     callback_url: string
         * } $config
         */
        $config = $container->get('config')['oauth-client']['mailru'];

        return new MailRu(
            client: new Client(),
            authUrl: $config['auth_url'],
            tokenUrl: $config['token_url'],
            apiUrl: $config['api_url'],
            clientId: $config['client_id'],
            clientSecret: $config['client_secret'],
            callbackUrl: $config['callback_url']
        );
    },

    'config' => [
        'oauth-client' => [
            'yandex' => [
                'auth_url' => env('OAUTH_YANDEX_AUTH_URL'),
                'token_url' => env('OAUTH_YANDEX_TOKEN_URL'),
                'api_url' => env('OAUTH_YANDEX_API_URL'),
                'client_id' => env('OAUTH_YANDEX_CLIENT_ID'),
                'client_secret' => env('OAUTH_YANDEX_CLIENT_SECRET'),
                'callback_url' => env('OAUTH_CALLBACK_URL'),
            ],
            'mailru' => [
                'auth_url' => env('OAUTH_MAILRU_AUTH_URL'),
                'token_url' => env('OAUTH_MAILRU_TOKEN_URL'),
                'api_url' => env('OAUTH_MAILRU_API_URL'),
                'client_id' => env('OAUTH_MAILRU_CLIENT_ID'),
                'client_secret' => env('OAUTH_MAILRU_CLIENT_SECRET'),
                'callback_url' => env('OAUTH_CALLBACK_URL'),
            ],
        ],
    ],
];

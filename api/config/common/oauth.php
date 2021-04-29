<?php

declare(strict_types=1);

use App\OAuth\Entity\AccessTokenRepository;
use App\OAuth\Entity\AuthCodeRepository;
use App\OAuth\Entity\Client;
use App\OAuth\Entity\ClientRepository;
use App\OAuth\Entity\RefreshTokenRepository;
use App\OAuth\Entity\Scope;
use App\OAuth\Entity\ScopeRepository;
use App\OAuth\Entity\UserRepository;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Psr\Container\ContainerInterface;
use function App\env;

return [
    ScopeRepositoryInterface::class => static function (ContainerInterface $container): ScopeRepository {
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{scopes: string[]} $config
         */
        $config = $container->get('config')['oauth'];

        return new ScopeRepository(
            array_map(static fn (string $item): Scope => new Scope($item), $config['scopes'])
        );
    },
    ClientRepositoryInterface::class => static function (ContainerInterface $container): ClientRepository {
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{
         *     clients: array<array-key, array{
         *         name: string,
         *         client_id: string,
         *         redirect_uri: string
         *     }>
         * } $config
         */
        $config = $container->get('config')['oauth'];

        return new ClientRepository(
            array_map(static function (array $item): Client {
                return new Client(
                    $item['client_id'],
                    $item['name'],
                    $item['redirect_uri']
                );
            }, $config['clients'])
        );
    },
    UserRepositoryInterface::class => DI\get(UserRepository::class),
    AccessTokenRepositoryInterface::class => DI\get(AccessTokenRepository::class),
    AuthCodeRepositoryInterface::class => DI\get(AuthCodeRepository::class),
    RefreshTokenRepositoryInterface::class => DI\get(RefreshTokenRepository::class),

    'config' => [
        'oauth' => [
            'scopes' => [
                'common',
            ],
            'clients' => [
                [
                    'name' => 'Auction',
                    'client_id' => 'frontend',
                    'redirect_uri' => env('FRONTEND_URL') . '/oauth',
                ],
            ],
        ],
    ],
];

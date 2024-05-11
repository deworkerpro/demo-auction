<?php

declare(strict_types=1);

use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordHasher;
use App\Auth\Service\Tokenizer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return [
    UserRepository::class => static function (ContainerInterface $container): UserRepository {
        $em = $container->get(EntityManagerInterface::class);
        $repo = $em->getRepository(User::class);
        return new UserRepository($em, $repo);
    },

    Tokenizer::class => static function (ContainerInterface $container): Tokenizer {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{token_ttl:string} $config
         */
        $config = $container->get('config')['auth'];

        return new Tokenizer(new DateInterval($config['token_ttl']));
    },

    PasswordHasher::class => static function (ContainerInterface $container): PasswordHasher {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{password_ttl:string} $config
         */
        $config = $container->get('config')['auth'];

        return new PasswordHasher(new DateInterval($config['password_ttl']));
    },

    'config' => [
        'auth' => [
            'password_ttl' => 'P2M',
            'token_ttl' => 'PT1H',
        ],
    ],
];

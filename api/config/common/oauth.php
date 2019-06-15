<?php

use Api\Infrastructure\Model\OAuth;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Middleware\ResourceServerMiddleware;
use League\OAuth2\Server\Repositories;
use League\OAuth2\Server\ResourceServer;
use Psr\Container\ContainerInterface;

return [
    'config' => [
        'oauth' => [
            'clients' => [
                'app' => [
                    'secret' => null,
                    'name' => 'App',
                    'redirect_uri' => null,
                    'is_confidential' => false,
                ],
            ],
            'privateKey' => dirname(__DIR__, 2) . '/' . getenv('OAUTH_PRIVATE_KEY_PATH'),
            'publicKey' => dirname(__DIR__, 2) . '/' . getenv('OAUTH_PUBLIC_KEY_PATH'),
            'encryptionKey' => getenv('OAUTH_ENCRYPTION_KEY'),
        ]
    ],

    AuthorizationServer::class => function (ContainerInterface $container) {
        $config = $container->get('config')['oauth'];

        $accessTokenRepository = $container->get(Repositories\AccessTokenRepositoryInterface::class);
        $refreshTokenRepository = $container->get(Repositories\RefreshTokenRepositoryInterface::class);
        $userRepository = $container->get(Repositories\UserRepositoryInterface::class);
        $scopeRepository = $container->get(Repositories\ScopeRepositoryInterface::class);
        $clientRepository = $container->get(Repositories\ClientRepositoryInterface::class);
        $authCodeRepository = $container->get(Repositories\AuthCodeRepositoryInterface::class);

        $server = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            new CryptKey($config['privateKey'], null, false),
            $config['encryptionKey']
        );

        $grantPassword = new PasswordGrant($userRepository, $refreshTokenRepository);
        $grantPassword->setRefreshTokenTTL(new DateInterval('P1M'));

        $grantRefresh = new RefreshTokenGrant($refreshTokenRepository);
        $grantRefresh->setRefreshTokenTTL(new DateInterval('P1M'));

        $grantAuthCode = new AuthCodeGrant($authCodeRepository, $refreshTokenRepository, new DateInterval('PT10M'));

        $server->enableGrantType($grantPassword, new DateInterval('PT1H'));
        $server->enableGrantType($grantAuthCode, new DateInterval('PT1H'));
        $server->enableGrantType($grantRefresh, new DateInterval('PT1H'));
        $server->enableGrantType(new ClientCredentialsGrant(), new DateInterval('PT1H'));
        $server->enableGrantType(new ImplicitGrant(new DateInterval('PT1H')));

        return $server;
    },

    ResourceServer::class => function (ContainerInterface $container) {
        $config = $container->get('config')['oauth'];

        return new ResourceServer(
            $container->get(Repositories\AccessTokenRepositoryInterface::class),
            new CryptKey($config['publicKey'], null, false)
        );
    },

    ResourceServerMiddleware::class => function(ContainerInterface $container) {
        return new ResourceServerMiddleware(
            $container->get(ResourceServer::class)
        );
    },

    Repositories\AccessTokenRepositoryInterface::class => function(ContainerInterface $container) {
        return new OAuth\Entity\AccessTokenRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    Repositories\AuthCodeRepositoryInterface::class => function(ContainerInterface $container) {
        return new OAuth\Entity\AuthCodeRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    Repositories\RefreshTokenRepositoryInterface::class => function(ContainerInterface $container) {
        return new OAuth\Entity\RefreshTokenRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    Repositories\UserRepositoryInterface::class => function(ContainerInterface $container) {
        return new OAuth\Entity\UserRepository(
            $container->get(EntityManagerInterface::class),
            $container->get(\Api\Model\User\Service\PasswordHasher::class)
        );
    },

    Repositories\ClientRepositoryInterface::class => function (ContainerInterface $container) {
        return new OAuth\Entity\ClientRepository($container->get('config')['oauth']['clients']);
    },

    Repositories\ScopeRepositoryInterface::class => function () {
        return new OAuth\Entity\ScopeRepository();
    },
];
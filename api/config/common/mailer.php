<?php

use Psr\Container\ContainerInterface;

return [
    'config' => [
        'mailer' => [
            'host' => getenv('APP_MAILER_HOST'),
            'port' => (int) getenv('APP_MAILER_PORT'),
            'username' => getenv('APP_MAILER_USERNAME'),
            'password' => getenv('APP_MAILER_PASSWORD'),
            'encryption' => getenv('APP_MAILER_ENCRYPTION'),
            'from' => [getenv('APP_MAILER_FROM_EMAIL') => 'App']
        ]
    ],

    Swift_Mailer::class => function (ContainerInterface $container) {
        $config = $container->get('config')['mailer'];

        $transport = (new Swift_SmtpTransport($config['host'], $config['port'], $config['encryption']))
            ->setUsername($config['username'])
            ->setPassword($config['password']);

        return new Swift_Mailer($transport);
    }
];
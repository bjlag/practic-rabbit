<?php

use Psr\Container\ContainerInterface;

return [
    'config' => [
        'mailer' => [
            'path' => 'var/mail',
        ]
    ],

    Swift_Mailer::class => function (ContainerInterface $container) {
        $config = $container->get('config')['mailer'];

        $transport = new \Geekdevs\SwiftMailer\Transport\FileTransport(
            new Swift_Events_SimpleEventDispatcher(),
            $config['path']
        );

        return new Swift_Mailer($transport);
    }
];
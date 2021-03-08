<?php

declare(strict_types=1);

return [
    'logger' => [
        //global processors
        'processors' => [],
        //global handlers
        'handlers' => [
            Monolog\Handler\StreamHandler::class => [
                'stream' => '/var/log/php.log'
            ],
        ],
        //logger services
        Monolog\Logger::class => [
            'channel_name' => 'application' //required
        ]
    ]
];
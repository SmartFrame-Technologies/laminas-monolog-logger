<?php

declare(strict_types=1);

use Monolog\Logger;
use Monolog\Processor\HostnameProcessor;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\WebProcessor;
use SmartFrame\Logger\Processor\ModuleProcessor;

return [
    'logger' => [
        //global processors
        'processors' => [
            MemoryPeakUsageProcessor::class => [],
            WebProcessor::class => [],
            IntrospectionProcessor::class => [],
            ModuleProcessor::class => [],
            HostnameProcessor::class => []
        ],
        //global handlers
        'handlers' => [
            Monolog\Handler\StreamHandler::class => [
                'stream' => '/var/log/global.log',
                'fingersCrossed' => Logger::DEBUG,
                'whatFailure' => true
            ],
        ],
        //logger services
        Monolog\Logger::class => [
            'channel_name' => 'php',
            //logger specific processors
            'processors' => [
                ProcessIdProcessor::class => []
            ],
            //logger specific handlers
            'handlers' => [
                Monolog\Handler\StreamHandler::class => [
                    'stream' => '/var/log/php.log'
                ],

            ]
        ],
        'ApplicationLogger' => [
            'channel_name' => 'application',
            'handlers' => [
                Monolog\Handler\StreamHandler::class => [
                    'stream' => '/var/log/application.log'
                ],
            ]
        ],
        'RequestLogger' => [
            'channel_name' => 'requests'
        ],
        'ErrorLogger' => [
            'channel_name' => 'errors',
            'processors' => [],
            'handlers' => [
                Monolog\Handler\ErrorLogHandler::class => []
            ]
        ],
        'ConsoleLogger' => [
            'channel_name' => 'console'
        ],
        'SecurityLogger' => [
            'channel_name' => 'security'
        ]
    ],
];
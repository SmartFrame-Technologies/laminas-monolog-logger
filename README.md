# laminas-monolog-logger
The `LaminasMonologLogger` provides integration of the [Monolog](https://github.com/Seldaek/monolog) library into the Laminas framework and Mezzio projects.

[![Build Status](https://travis-ci.com/SmartFrame-Technologies/laminas-monolog-logger.svg?branch=master)](https://travis-ci.com/SmartFrame-Technologies/laminas-monolog-logger)
[![Coverage Status](https://coveralls.io/repos/github/SmartFrame-Technologies/laminas-monolog-logger/badge.svg?branch=master)](https://coveralls.io/github/SmartFrame-Technologies/laminas-monolog-logger?branch=master)


## Installation

Install the latest version with

```bash
$ composer require smartframe-technologies/laminas-monolog-logger
```

## Configuration

Start by creating a logging configuration file (i.e. `config/autoload/logger.global.php`) with minimal configration

If are you using [ConfigAggregator](https://github.com/laminas/laminas-config-aggregator/) library already have defined `ConfigProvider`\
More information in [ConfigProviders](https://docs.laminas.dev/laminas-config-aggregator/config-providers/) section



## Minimal config settings
```php
<?php
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
```

## Full config settings
```php
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
```

## License

Copyright 2020 SmartFrame Technologies

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at:

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

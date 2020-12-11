<?php

declare(strict_types=1);

namespace SmartFrameTest\Logger\Factory;

use Interop\Container\ContainerInterface;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\WhatFailureGroupHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use SmartFrame\Logger\HandlerFactory\ElasticsearchHandlerFactory;

class ElasticsearchHandlerFactoryTest extends TestCase
{

    public function testElasticsearchHandlerInstance()
    {
        $containerMock = $this->createMock(ContainerInterface::class);

        $containerMock
            ->method('get')
            ->willReturnMap([
                ['config', [
                    'elasticsearch' => [
                        'hosts' => []
                    ],
                    'logger' => [
                        'handlers' => [
                            ElasticsearchHandler::class => []
                        ]
                    ]
                ]]
            ]);

        $instance = (new ElasticsearchHandlerFactory())->__invoke($containerMock, ElasticsearchHandler::class);

        self::assertInstanceOf(ElasticsearchHandler::class, $instance);
    }

    public function testWhatFailureHandlerInstance()
    {
        $containerMock = $this->createMock(ContainerInterface::class);

        $containerMock
            ->method('get')
            ->willReturnMap([
                ['config', [
                    'elasticsearch' => [
                        'hosts' => []
                    ],
                    'logger' => [
                        'handlers' => [
                            ElasticsearchHandler::class => [
                                'whatFailure' => true
                            ]
                        ]
                    ]
                ]
                ]
            ]);

        $instance = (new ElasticsearchHandlerFactory())->__invoke($containerMock, ElasticsearchHandler::class);

        self::assertInstanceOf(WhatFailureGroupHandler::class, $instance);
    }

    public function testFingersCrossedHandlerInstance()
    {
        $containerMock = $this->createMock(ContainerInterface::class);

        $containerMock
            ->method('get')
            ->willReturnMap([
                ['config', [
                    'elasticsearch' => [
                        'hosts' => []
                    ],
                    'logger' => [
                        'handlers' => [
                            ElasticsearchHandler::class => [
                                'fingersCrossed' => Logger::INFO
                            ]
                        ]
                    ]
                ]
                ]
            ]);

        $instance = (new ElasticsearchHandlerFactory())->__invoke($containerMock, ElasticsearchHandler::class);

        self::assertInstanceOf(FingersCrossedHandler::class, $instance);
    }
}

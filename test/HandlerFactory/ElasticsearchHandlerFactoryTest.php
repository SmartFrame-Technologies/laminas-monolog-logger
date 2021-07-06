<?php

declare(strict_types=1);

namespace SmartFrameTest\Logger\HandlerFactory;

use Interop\Container\ContainerInterface;
use InvalidArgumentException;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\WhatFailureGroupHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use SmartFrame\Logger\HandlerFactory\ElasticsearchHandlerFactory;
use SmartFrame\Logger\HandlerFactory\MissingConfigurationException;

class ElasticsearchHandlerFactoryTest extends TestCase
{

    public function testElasticsearchHandlerInstance(): void
    {
        $containerMock = $this->createMock(ContainerInterface::class);

        $containerMock
            ->method('get')
            ->willReturnMap([
                ['config', [
                    'elasticsearch' => [
                        'hosts' => []
                    ],
                ]]
            ]);

        $instance = (new ElasticsearchHandlerFactory())->__invoke($containerMock, ElasticsearchHandler::class, []);

        self::assertInstanceOf(ElasticsearchHandler::class, $instance);
    }

    public function testWhatFailureHandlerInstance(): void
    {
        $containerMock = $this->createMock(ContainerInterface::class);

        $containerMock
            ->method('get')
            ->willReturnMap([
                ['config', [
                    'elasticsearch' => [
                        'hosts' => []
                    ],
                ]]
            ]);

        $instance = (new ElasticsearchHandlerFactory())->__invoke($containerMock, ElasticsearchHandler::class,
            [
                'whatFailure' => true
            ]
        );

        self::assertInstanceOf(WhatFailureGroupHandler::class, $instance);
    }

    public function testFingersCrossedHandlerInstance(): void
    {
        $containerMock = $this->createMock(ContainerInterface::class);

        $containerMock
            ->method('get')
            ->willReturnMap([
                ['config', [
                    'elasticsearch' => [
                        'hosts' => []
                    ],
                ]]
            ]);

        $instance = (new ElasticsearchHandlerFactory())->__invoke($containerMock, ElasticsearchHandler::class, [
            'fingersCrossed' => Logger::INFO
        ]);

        self::assertInstanceOf(FingersCrossedHandler::class, $instance);
    }

    public function testInstanceWithCustomFormatter()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ElasticsearchHandler is only compatible with ElasticsearchFormatter');

        $containerMock = $this->createMock(ContainerInterface::class);

        $containerMock
            ->method('get')
            ->willReturnMap([
                ['config', [
                    'elasticsearch' => [
                        'hosts' => []
                    ],
                ]]
            ]);

        (new ElasticsearchHandlerFactory())($containerMock, ElasticsearchHandler::class, [
            'formatter' => [
                'type' => JsonFormatter::class
            ]
        ]);
    }

    public function testInvokeMissingElasticsearchConfiguration()
    {
        $this->expectException(MissingConfigurationException::class);

        $containerMock = $this->createMock(ContainerInterface::class);

        (new ElasticsearchHandlerFactory())($containerMock, ElasticsearchHandler::class, []);
    }

    public function testInstanceWithPropertiesSetter(): void
    {
        $containerMock = $this->createMock(ContainerInterface::class);

        $containerMock
            ->method('get')
            ->willReturnMap([
                ['config', [
                    'elasticsearch' => [
                        'hosts' => []
                    ],
                ]]
            ]);

        $handler = (new ElasticsearchHandlerFactory())($containerMock, ElasticsearchHandler::class, [
            'properties' => [
                'bubble' => false
            ]
        ]);

        self::assertEquals(false, $handler->getBubble());
    }
}

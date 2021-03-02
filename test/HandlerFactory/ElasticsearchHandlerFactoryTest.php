<?php

declare(strict_types=1);

namespace SmartFrameTest\Logger\HandlerFactory;

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
                ]]
            ]);

        $instance = (new ElasticsearchHandlerFactory())->__invoke($containerMock, ElasticsearchHandler::class, []);

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
                ]]
            ]);

        $instance = (new ElasticsearchHandlerFactory())->__invoke($containerMock, ElasticsearchHandler::class,
            [
                'whatFailure' => true
            ]
        );

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
                ]]
            ]);

        $instance = (new ElasticsearchHandlerFactory())->__invoke($containerMock, ElasticsearchHandler::class, [
            'fingersCrossed' => Logger::INFO
        ]);

        self::assertInstanceOf(FingersCrossedHandler::class, $instance);
    }
}

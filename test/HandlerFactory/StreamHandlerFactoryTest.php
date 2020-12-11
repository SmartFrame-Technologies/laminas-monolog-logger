<?php

declare(strict_types=1);

namespace SmartFrameTest\Logger\HandlerFactory;

use Interop\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;
use PHPUnit\Framework\TestCase;
use SmartFrame\Logger\HandlerFactory\StreamHandlerFactory;

class StreamHandlerFactoryTest extends TestCase
{

    public function testInvoke(): void
    {
        $containerMock = $this->createMock(ContainerInterface::class);

        $containerMock
            ->method('get')
            ->willReturnMap([
                ['config', [
                    'logger' => [
                        'handlers' => [
                            StreamHandler::class => ['stream' => 'data/logs/test.log']
                        ]
                    ]
                ]]
            ]);

        $instance = (new StreamHandlerFactory())->__invoke($containerMock, StreamHandler::class);

        self::assertInstanceOf(StreamHandler::class, $instance);
    }
}

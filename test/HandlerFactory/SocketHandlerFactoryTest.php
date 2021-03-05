<?php

declare(strict_types=1);

namespace SmartFrameTest\Logger\HandlerFactory;

use Interop\Container\ContainerInterface;
use Monolog\Formatter\LogstashFormatter;
use Monolog\Handler\SocketHandler;
use PHPUnit\Framework\TestCase;
use SmartFrame\Logger\HandlerFactory\MissingOptionsException;
use SmartFrame\Logger\HandlerFactory\SocketHandlerFactory;

class SocketHandlerFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $containerMock = $this->createMock(ContainerInterface::class);

        $instance = (new SocketHandlerFactory())->__invoke($containerMock, SocketHandler::class, ['connectionString' => 'tcp://127.0.0.1:5000']);

        self::assertInstanceOf(SocketHandler::class, $instance);
    }

    public function testInstanceWithCustomFormatter(): void
    {
        $containerMock = $this->createMock(ContainerInterface::class);

        $handler = (new SocketHandlerFactory())($containerMock, SocketHandler::class, [
            'connectionString' => 'tcp://127.0.0.1:5000',
            'formatter' => [
                'type' => LogstashFormatter::class,
                'arguments' => [
                    'applicationName'
                ]
            ]
        ]);

        self::assertInstanceOf(LogstashFormatter::class, $handler->getFormatter());
    }

    public function testInvokeMissingStreamArgument(): void
    {
        $this->expectException(MissingOptionsException::class);

        $containerMock = $this->createMock(ContainerInterface::class);

        (new SocketHandlerFactory())($containerMock, SocketHandler::class, []);
    }
}
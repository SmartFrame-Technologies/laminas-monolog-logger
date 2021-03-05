<?php

declare(strict_types=1);

namespace SmartFrameTest\Logger\HandlerFactory;

use Interop\Container\ContainerInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use PHPUnit\Framework\TestCase;
use SmartFrame\Logger\HandlerFactory\MissingOptionsException;
use SmartFrame\Logger\HandlerFactory\StreamHandlerFactory;

class StreamHandlerFactoryTest extends TestCase
{

    public function testInvoke(): void
    {
        $containerMock = $this->createMock(ContainerInterface::class);

        $instance = (new StreamHandlerFactory())->__invoke($containerMock, StreamHandler::class, ['stream' => 'data/logs/test.log']);

        self::assertInstanceOf(StreamHandler::class, $instance);
    }

    public function testInstanceWithCustomFormatter(): void
    {
        $containerMock = $this->createMock(ContainerInterface::class);

        $handler = (new StreamHandlerFactory())($containerMock, StreamHandler::class, [
            'stream' => 'data/logs/test.log',
            'formatter' => [
                'type' => JsonFormatter::class
            ]
        ]);

        self::assertInstanceOf(JsonFormatter::class, $handler->getFormatter());
    }

    public function testInvokeMissingStreamArgument(): void
    {
        $this->expectException(MissingOptionsException::class);

        $containerMock = $this->createMock(ContainerInterface::class);

        (new StreamHandlerFactory())($containerMock, StreamHandler::class, []);
    }
}
